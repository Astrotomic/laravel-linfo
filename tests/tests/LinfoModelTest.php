<?php

use Carbon\Carbon;
use Linfo\Laravel\Models\Linfo as LinfoModel;

class LinfoModelTest extends TestCase
{
    public function testInstanceOf()
    {
        $this->assertInstanceOf(LinfoModel::class, $this->getModelInstance());
    }

    public function testOriginals()
    {
        $linfo = $this->getModelInstance();
        $originals = $linfo->getOriginals();
        $this->assertInternalType('array', $originals);
        $this->assertArrayHasKey('OS', $originals);
        $this->assertInternalType('string', $linfo->getOriginal('OS'));
    }

    public function testAttributes()
    {
        $linfo = $this->getModelInstance();
        $attributes = $linfo->getAttributes();
        $this->assertInternalType('array', $attributes);
        $this->assertArrayHasKey('os', $attributes);
        $this->assertInternalType('string', $linfo->getAttribute('os'));
    }

    public function testProcesseds()
    {
        $linfo = $this->getModelInstance();
        $processeds = $linfo->getProcesseds();
        $this->assertInternalType('array', $processeds);
        $this->assertArrayHasKey('os', $processeds);
        $this->assertInternalType('array', $linfo->getProcessed('os'));
    }

    public function testUptime()
    {
        $linfo = $this->getModelInstance();
        $uptime = $linfo->uptime;
        $this->assertInternalType('array', $uptime);
        $this->assertInternalType('string', $uptime['text']);
        $this->assertInstanceOf(Carbon::class, $uptime['bootedtimestamp']);
    }

    public function testToArray()
    {
        $linfo = $this->getModelInstance();
        $attributes = $linfo->toArray();
        $this->assertInternalType('array', $attributes);
        $this->assertArrayHasKey('os', $attributes);
    }
}
