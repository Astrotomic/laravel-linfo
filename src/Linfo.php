<?php

namespace Linfo\Laravel;

use Carbon\Carbon;

/**
 * @property string|null $phpversion
 * @property string|null $webservice
 * @property-read Carbon $booted_at
 * @property-read Carbon $created_at
 */
class Linfo extends Model
{
    protected $dates = [
        'timestamp',
    ];

    public function setUptimeAttribute($value): void
    {
        if (is_array($value)) {
            $this->attributes['uptime'] = $value;
            $this->attributes['uptime']['bootedtimestamp'] = $this->fromDateTime($value['bootedtimestamp']);
        } else {
            $valArray = explode(';', $value);
            $uptime = trim($valArray[0]);
            $bootedtime = trim(explode(' ', trim($valArray[1]))[1]);
            $this->attributes['uptime']['text'] = $uptime;
            $this->attributes['uptime']['bootedtimestamp'] = $this->fromDateTime($bootedtime);
        }
    }

    public function getUptimeAttribute(array $value): array
    {
        return [
            'text' => $value['text'],
            'bootedtimestamp' => $this->asDateTime($value['bootedtimestamp']),
        ];
    }

    public function getBootedAtAttribute(): Carbon
    {
        return $this['uptime']['bootedtimestamp'];
    }

    public function getCreatedAtAttribute(): Carbon
    {
        return $this['timestamp'];
    }

    public function setPhpversionAttribute(?string $value): void
    {
        $this->attributes['phpversion'] = $value ?: phpversion();
    }

    public function setWebserviceAttribute(?string $value): void
    {
        $this->attributes['webservice'] = $value ?: (!empty($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : null);
    }
}
