<?php
namespace Gummibeer\Linfo\Models;

use Gummibeer\Linfo\Traits\LinfoProcessedTrait;

class Linfo extends Model
{
    use LinfoProcessedTrait;

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
            $this->setProcesseds();
        } catch (\LinfoFatalException $e) {
            die($e->getMessage());
        }
    }

    // Attribute Setter
    public function setUptimeAttribute($value)
    {
        $this->attributes['uptime'] = $value;
        $this->attributes['uptime']['bootedtimestamp'] = $this->asDateTime($value['bootedtimestamp']);
    }
}