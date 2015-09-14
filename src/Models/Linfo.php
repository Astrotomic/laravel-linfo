<?php
namespace Linfo\Laravel\Models;

use Linfo\Laravel\Traits\LinfoProcessedTrait;

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
            $this->setup($linfo->getInfo());
        } catch (\LinfoFatalException $e) {
            die($e->getMessage());
        }
    }

    protected function setup(array $attributes)
    {
        $this->setOriginals($attributes);
        $this->setAttributes($this->originals);
        $this->setProcesseds();
    }

    // Attribute Setter
    public function setUptimeAttribute($value)
    {
        $this->attributes['uptime'] = $value;
        $this->attributes['uptime']['bootedtimestamp'] = $this->asDateTime($value['bootedtimestamp']);
    }
}