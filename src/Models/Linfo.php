<?php
namespace Gummibeer\Linfo\Models;

class Linfo extends Model
{
    public function __construct()
    {
        try {
            $linfo = new \Linfo(config('linfo.source'));
            $linfo->scan();
            $this->originals = $linfo->getInfo();
        } catch (\LinfoFatalException $e) {
            die($e->getMessage());
        }
    }
}