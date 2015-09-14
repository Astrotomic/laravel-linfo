<?php
namespace Linfo\Laravel\Traits;

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
            $cpu['model'] = str_ireplace('(R)', '&reg;', preg_replace('/[\s]{2,}/', ' ', $orgCPU[0]['model']));
            $cpu['mhz'] = $orgCPU[0]['mhz'] * 1;
            $cpu['ghz'] = $cpu['mhz'] / 1000;
            $cpu['cores'] = count($orgCPU);
            $cpu['usage_percentage'] = ceil(array_sum($usage_percentage) / count($usage_percentage)) ?: 1;
        }
        if(!empty(array_get($this->attributes, 'cpuarchitecture'))) {
            $cpu['architecture'] = array_get($this->attributes, 'cpuarchitecture');
        }
        return $cpu;
    }

    public function setRamProcessed()
    {
        $ram = [];
        if(!empty(array_get($this->attributes, 'ram'))) {
            $orgRAM = array_get($this->attributes, 'ram');
            $ram['type'] = strtolower($orgRAM['type']);
            $ram['total'] = $orgRAM['total'];
            $ram['total_gb'] = $this->b2Gb($ram['total']);
            $ram['free'] = $orgRAM['free'];
            $ram['free_gb'] = $this->b2Gb($ram['free']);
            $ram['blocked'] = $orgRAM['total'] - $orgRAM['free'];
            $ram['blocked_gb'] = $this->b2Gb($ram['blocked']);
            $ram['usage_percentage'] = $ram['blocked'] / $ram['total'] * 100;
        }
        return $ram;
    }

    public function setSwapProcessed()
    {
        $swap = [];
        if(!empty(array_get($this->attributes, 'ram'))) {
            $orgRAM = array_get($this->attributes, 'ram');
            $swap['total'] = $orgRAM['swaptotal'];
            $swap['total_gb'] = $this->b2Gb($swap['total']);
            $swap['free'] = $orgRAM['swapfree'];
            $swap['free_gb'] = $this->b2Gb($swap['free']);
            $swap['blocked'] = $swap['total'] - $swap['free'];
            $swap['blocked_gb'] = $this->b2Gb($swap['blocked']);
            $swap['cached'] = $orgRAM['swapcached'];
            $swap['cached_gb'] = $this->b2Gb($swap['cached']);
            $swap['usage_percentage'] = $swap['blocked'] / $swap['total'] * 100;
        }
        return $swap;
    }

    public function setOsProcessed()
    {
        $os = [];
        if(!empty(array_get($this->attributes, 'os'))) {
            $os['type'] = $this->attributes['os'];
        }
        if(!empty(array_get($this->attributes, 'kernel'))) {
            $os['kernel'] = $this->attributes['kernel'];
        }
        if(!empty(array_get($this->attributes, 'distro'))) {
            $os['name'] = $this->attributes['distro']['name'];
            $os['version'] = $this->attributes['distro']['version'];
        }
        return $os;
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

    // Helper
    protected function b2Gb($byte)
    {
        return $byte / 1024 / 1024 / 1024;
    }
}