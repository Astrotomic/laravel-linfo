<?php
namespace Linfo\Laravel\Models;

use DateTime;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Str;

class Model implements Arrayable, Jsonable
{
    protected $originals = [];
    protected $attributes = [];

    protected $hidden = [];

    protected $dates = [];
    protected $casts = [];

    /**
     * SETTER
     */
    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }

    public function setAttribute($key, $value)
    {
        if ($this->hasSetMutator($key)) {
            $method = 'set' . Str::studly($key) . 'Attribute';
            return $this->{$method}($value);
        } elseif (in_array($key, $this->dates) && $value) {
            $value = $this->asDateTime($value);
        }

        if ($this->isJsonCastable($key) && !is_null($value)) {
            $value = json_encode($value);
        }

        $this->attributes[$key] = $value;
    }

    public function hasSetMutator($key)
    {
        return method_exists($this, 'set' . Str::studly($key) . 'Attribute');
    }

    protected function isJsonCastable($key)
    {
        if ($this->hasCast($key)) {
            return in_array(
                $this->getCastType($key), ['array', 'json', 'object', 'collection'], true
            );
        }

        return false;
    }

    protected function setAttributes(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            if (is_array($value)) {
                $value = $this->slugArrayKeys($value);
            }
            $this->setAttribute(Str::slug($key, '_'), $value);
        }
    }

    protected function setOriginals(array $attributes)
    {
        $this->originals = $attributes;
    }

    /**
     * GETTER
     */
    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    public function getOriginals()
    {
        return $this->originals;
    }

    public function getOriginal($key)
    {
        return array_get($this->originals, $key);
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function getAttribute($key)
    {
        if (array_get($this->attributes, $key) != null || $this->hasGetMutator($key)) {
            return $this->getAttributeValue($key);
        }
    }

    public function getAttributeValue($key)
    {
        $value = $this->getAttributeFromArray($key);

        if ($this->hasGetMutator($key)) {
            return $this->mutateAttribute($key, $value);
        }

        if ($this->hasCast($key)) {
            $value = $this->castAttribute($key, $value);
        } elseif (in_array($key, $this->dates)) {
            if (!is_null($value)) {
                return $this->asDateTime($value);
            }
        }

        return $value;
    }

    protected function getAttributeFromArray($key)
    {
        return array_get($this->attributes, $key);
    }

    protected function hasGetMutator($key)
    {
        return method_exists($this, 'get' . Str::studly($key) . 'Attribute');
    }

    protected function mutateAttribute($key, $value)
    {
        return $this->{'get' . Str::studly($key) . 'Attribute'}($value);
    }

    protected function hasCast($key)
    {
        return array_key_exists($key, $this->casts);
    }

    protected function castAttribute($key, $value)
    {
        if (is_null($value)) {
            return $value;
        }

        switch ($this->getCastType($key)) {
            case 'int':
            case 'integer':
                return (int)$value;
            case 'real':
            case 'float':
            case 'double':
                return (float)$value;
            case 'string':
                return (string)$value;
            case 'bool':
            case 'boolean':
                return (bool)$value;
            case 'object':
                return json_decode($value);
            case 'array':
            case 'json':
                return json_decode($value, true);
            default:
                return $value;
        }
    }

    protected function getCastType($key)
    {
        return trim(strtolower($this->casts[$key]));
    }

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
     * HELPERS
     */
    protected function slugArrayKeys(array $array)
    {
        $tmp = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $tmp[Str::slug($key, '_')] = $this->slugArrayKeys($value);
            } else {
                $tmp[Str::slug($key, '_')] = $value;
            }
        }
        return $tmp;
    }

    public function toArray()
    {
        return json_decode($this->toJson(), true);
    }

    public function toJson($options = 0)
    {
        return json_encode(array_diff_key($this->attributes, array_flip($this->hidden)));
    }
}