<?php

namespace Linfo\Laravel\Tests;

use Carbon\Carbon;
use Linfo\Laravel\Linfo;

class LinfoModelTest extends TestCase
{
    public function testInstanceOf()
    {
        $this->assertInstanceOf(Linfo::class, $this->getModelInstance());
    }

    public function testOriginals()
    {
        $linfo = $this->getModelInstance();
        $originals = $linfo->getOriginals();
        $this->assertIsArray($originals);
        $this->assertArrayHasKey('os', $originals);
        $this->assertIsString($linfo->getOriginal('os'));
    }

    public function testAttributes()
    {
        $linfo = $this->getModelInstance();
        $attributes = $linfo->getAttributes();
        $this->assertIsArray($attributes);
        $this->assertArrayHasKey('os', $attributes);
        $this->assertIsString($linfo->getAttribute('os'));
    }

    public function testUptime()
    {
        $linfo = $this->getModelInstance();
        $uptime = $linfo->uptime;
        $this->assertIsArray($uptime);
        $this->assertIsString($uptime['text']);
        $this->assertInstanceOf(Carbon::class, $uptime['bootedtimestamp']);
    }

    public function testToArray()
    {
        $linfo = $this->getModelInstance();
        $attributes = $linfo->toArray();
        $this->assertIsArray($attributes);
        $this->assertArrayHasKey('os', $attributes);
    }
}
