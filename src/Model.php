<?php

namespace Linfo\Laravel;

use ArrayAccess;
use DateTimeInterface;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Database\Eloquent\Concerns\HasAttributes;
use Illuminate\Database\Eloquent\Concerns\HidesAttributes;
use Illuminate\Support\Str;
use JsonSerializable;
use Linfo\Linfo as LinfoBase;

/** @internal */
abstract class Model implements Arrayable, ArrayAccess, Jsonable, JsonSerializable
{
    use HasAttributes, HidesAttributes;

    public function __construct()
    {
        $this->refresh();
    }

    public function refresh(): self
    {
        $linfo = new LinfoBase(config('linfo.source'));
        $linfo->scan();
        foreach($this->normalizeArrayKeys($linfo->getInfo()) as $key => $value) {
            $this->setAttribute($key, $value);
        }
        $this->syncOriginal();
        $this->classCastCache = [];

        return $this;
    }

    public function fresh(): self
    {
        return new static();
    }

    public function getOriginals(): array
    {
        return $this->original;
    }

    protected function normalizeArrayKeys(array $array): array
    {
        $tmp = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = $this->normalizeArrayKeys($value);
            }
            $tmp[Str::slug($key, '_')] = $value;
        }

        return $tmp;
    }

    protected function getIncrementing(): bool
    {
        return false;
    }

    protected function usesTimestamps(): bool
    {
        return false;
    }

    public function getCreatedAtColumn(): ?string
    {
        return null;
    }

    public function getUpdatedAtColumn(): ?string
    {
        return null;
    }

    public function getRelationValue($key)
    {
        return null;
    }

    public function getDateFormat()
    {
        return DateTimeInterface::ATOM;
    }

    public function toArray(): array
    {
        return $this->attributesToArray();
    }

    public function toJson($options = 0): string
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function offsetExists($offset): bool
    {
        return $this->getAttribute($offset) !== null;
    }

    public function offsetGet($offset)
    {
        return $this->getAttribute($offset);
    }

    public function offsetSet($offset, $value): void
    {
        $this->setAttribute($offset, $value);
    }

    public function offsetUnset($offset): void
    {
        unset($this->attributes[$offset]);
    }

    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    public function __set($key, $value): void
    {
        $this->setAttribute($key, $value);
    }

    public function __isset($key): bool
    {
        return $this->offsetExists($key);
    }

    public function __unset($key): void
    {
        $this->offsetUnset($key);
    }
}
