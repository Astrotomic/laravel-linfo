<?php
namespace Gummibeer\Linfo\Traits;

use Illuminate\Support\Str;

trait LinfoProcessedTrait
{
    protected $processeds = [];

    // Processed Setter
    public function setBootedAtProcessed()
    {
        if(!empty(array_get($this->attributes, 'uptime.bootedtimestamp'))) {
            return $this->asDateTime(array_get($this->attributes, 'uptime.bootedtimestamp'))->setTimezone('UTC');
        }
    }

    public function setCreatedAtProcessed()
    {
        if(!empty(array_get($this->attributes, 'timestamp'))) {
            return $this->asDateTime(array_get($this->attributes, 'timestamp'))->setTimezone('UTC');
        }
    }

    public function setPhpVersionProcessed()
    {
        return phpversion();
    }

    public function setWebServerProcessed()
    {
        return $_SERVER['SERVER_SOFTWARE'];
    }

    public function setCpuProcessed()
    {
        $cpu = [];
        if(!empty(array_get($this->attributes, 'cpu'))) {
            $orgCPU = array_get($this->attributes, 'cpu');
            $usage_percentage = array_column($orgCPU, 'usage_percentage');
            $cpu['vendor'] = $orgCPU[0]['vendor'];
            $cpu['model'] = preg_replace('/[\s]{2,}/', ' ', $orgCPU[0]['model']);
            $cpu['mhz'] = $orgCPU[0]['mhz'] * 1;
            $cpu['cores'] = count($orgCPU);
            $cpu['usage_percentage'] = ceil(array_sum($usage_percentage) / count($usage_percentage)) ?: 1;
        }
        if(!empty(array_get($this->attributes, 'cpuarchitecture'))) {
            $cpu['architecture'] = array_get($this->attributes, 'cpuarchitecture');
        }
        return $cpu;
    }

    // Processed Helper
    public function getProcesseds()
    {
        return $this->processeds;
    }

    public function getProcessed($key)
    {
        if (array_key_exists($key, $this->processeds)) {
            return $this->processeds[$key];
        }
    }

    public function setProcesseds()
    {
        $pattern = '/^set([a-zA-Z]+)Processed$/';
        $methods = preg_grep($pattern, get_class_methods($this));
        foreach ($methods as $method) {
            preg_match($pattern, $method, $matches);
            $this->processeds[Str::snake($matches[1])] = $this->{$matches[0]}();
        }
        $this->processeds = array_filter($this->processeds);
    }
}