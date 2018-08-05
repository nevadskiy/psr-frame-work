<?php

namespace Framework\Container;

class Container
{
    private $services = [];

    private $cache = [];

    public function get($key)
    {
        if (array_key_exists($key, $this->cache)) {
            return $this->cache[$key];
        }

        if (!array_key_exists($key, $this->services)) {
            throw new ServiceNotFoundException('Undefined parameter "'.$key.'"');
        }

        $definition = $this->services[$key];

        if ($definition instanceof \Closure) {
            $this->cache[$key] = $definition($this);
        } else {
            $this->cache[$key] = $definition;
        }

        return $this->cache[$key];
    }

    public function set($key, $value): void
    {
        if (array_key_exists($key, $this->cache)) {
            unset($this->cache[$key]);
        }

        $this->services[$key] = $value;
    }
}