<?php
namespace Gummibeer\Linfo\Models;

use Carbon\Carbon;

class Linfo extends Model
{
    protected $dates = [
        'timestamp',
    ];

    public function __construct()
    {
        try {
            $linfo = new \Linfo(config('linfo.source'));
            $linfo->scan();
            $this->originals = $linfo->getInfo();
            $this->setAttributes($this->originals);
        } catch (\LinfoFatalException $e) {
            die($e->getMessage());
        }
    }

    public function setUptimeAttribute($value)
    {
        $value = trim(substr($value, strpos($value, 'booted') + 6));
        $this->attributes['uptime'] = Carbon::createFromTimestampUTC($value);
    }
}