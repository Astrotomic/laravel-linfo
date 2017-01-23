<?php

namespace Linfo\Laravel\Models;

use DateTime;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Class Model.
 */
class Model implements Arrayable, Jsonable
{
    /**
     * @var array
     */
    protected $originals = [];
    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @var array
     */
    protected $hidden = [];

    /**
     * @var array
     */
    protected $dates = [];
    /**
     * @var array
     */
    protected $casts = [];

    /**
     * @param $key
     * @param $value
     */
    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    public function setAttribute($key, $value)
    {
        if ($this->hasSetMutator($key)) {
            $method = 'set'.Str::studly($key).'Attribute';

            return $this->{$method}($value);
        } elseif (in_array($key, $this->dates) && $value) {
            $value = $this->asDateTime($value);
        }

        if ($this->isJsonCastable($key) && ! is_null($value)) {
            $value = json_encode($value);
        }

        $this->attributes[$key] = $value;
    }

    /**
     * @param $key
     * @return bool
     */
    public function hasSetMutator($key)
    {
        return method_exists($this, 'set'.Str::studly($key).'Attribute');
    }

    /**
     * @param $key
     * @return bool
     */
    protected function isJsonCastable($key)
    {
        if ($this->hasCast($key)) {
            return in_array(
                $this->getCastType($key), ['array', 'json', 'object', 'collection'], true
            );
        }

        return false;
    }

    /**
     * @param array $attributes
     */
    protected function setAttributes(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            if (is_array($value)) {
                $value = $this->slugArrayKeys($value);
            }
            $this->setAttribute(Str::slug($key, '_'), $value);
        }
    }

    /**
     * @param array $attributes
     */
    protected function setOriginals(array $attributes)
    {
        $this->originals = $attributes;
    }

    /**
     * @param $key
     * @return bool|Carbon|mixed|static
     */
    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    /**
     * @return array
     */
    public function getOriginals()
    {
        return $this->originals;
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function getOriginal($key, $default = null)
    {
        return array_get($this->originals, $key, $default);
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param $key
     * @param null $default
     * @return bool|Carbon|mixed|static
     */
    public function getAttribute($key, $default = null)
    {
        if (array_get($this->attributes, $key) != null || $this->hasGetMutator($key)) {
            return $this->getAttributeValue($key, $default);
        }
    }

    /**
     * @param $key
     * @param null $default
     * @return bool|Carbon|mixed|static
     */
    public function getAttributeValue($key, $default = null)
    {
        $value = $this->getAttributeFromArray($key, $default);

        if ($this->hasGetMutator($key)) {
            return $this->mutateAttribute($key, $value);
        }

        if ($this->hasCast($key)) {
            $value = $this->castAttribute($key, $value);
        } elseif (in_array($key, $this->dates)) {
            if (! is_null($value)) {
                return $this->asDateTime($value);
            }
        }

        return $value;
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed
     */
    protected function getAttributeFromArray($key, $default = null)
    {
        return array_get($this->attributes, $key, $default);
    }

    /**
     * @param $key
     * @return bool
     */
    protected function hasGetMutator($key)
    {
        return method_exists($this, 'get'.Str::studly($key).'Attribute');
    }

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    protected function mutateAttribute($key, $value)
    {
        return $this->{'get'.Str::studly($key).'Attribute'}($value);
    }

    /**
     * @param $key
     * @return bool
     */
    protected function hasCast($key)
    {
        return array_key_exists($key, $this->casts);
    }

    /**
     * @param $key
     * @param $value
     * @return bool|mixed
     */
    protected function castAttribute($key, $value)
    {
        if (is_null($value)) {
            return $value;
        }

        switch ($this->getCastType($key)) {
            case 'int':
            case 'integer':
                return (int) $value;
            case 'real':
            case 'float':
            case 'double':
                return (float) $value;
            case 'string':
                return (string) $value;
            case 'bool':
            case 'boolean':
                return (bool) $value;
            case 'object':
                return json_decode($value);
            case 'array':
            case 'json':
                return json_decode($value, true);
            default:
                return $value;
        }
    }

    /**
     * @param $key
     * @return string
     */
    protected function getCastType($key)
    {
        return trim(strtolower($this->casts[$key]));
    }

    /**
     * @param $value
     * @return Carbon
     */
    protected function asDateTime($value)
    {
        if ($value instanceof Carbon) {
            return $value;
        }

        if ($value instanceof DateTime) {
            return Carbon::instance($value);
        }

        if (is_numeric($value)) {
            return Carbon::createFromTimestamp($value);
        }

        if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $value)) {
            return Carbon::createFromFormat('Y-m-d', $value)->startOfDay();
        }

        return new Carbon($value);
    }

    /**
     * @param array $array
     * @return array
     */
    protected function slugArrayKeys(array $array)
    {
        $tmp = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = $this->slugArrayKeys($value);
            }
            $tmp[Str::slug($key, '_')] = $value;
        }

        return $tmp;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return json_decode($this->toJson(), true);
    }

    /**
     * @param int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode(array_diff_key($this->attributes, array_flip($this->hidden)), $options);
    }
}
