<?php

namespace Framework\Container;

use Psr\Container\ContainerInterface;
use ReflectionClass;

class Container implements ContainerInterface
{
    private $services = [];

    private $cache = [];

    public function __construct(array $config = [])
    {
        $this->services = $config;
    }

    public function get($id)
    {
        if (array_key_exists($id, $this->cache)) {
            return $this->cache[$id];
        }

        if (!array_key_exists($id, $this->services)) {
            if (class_exists($id)) {

                // Resolving service dependencies
                $reflection = new ReflectionClass($id);
                $arguments = [];

                if (($constructor = $reflection->getConstructor()) !== null) {
                    foreach ($constructor->getParameters() as $param) {
                        if ($paramClass = $param->getClass()) {
                            $arguments[] = $this->get($paramClass->getName());
                        } elseif ($param->isArray()) {
                            $arguments[] = [];
                        } else {
                            if (!$param->isDefaultValueAvailable()) {
                                throw new ServiceNotFoundException('Unable to resolve "' . $param->getName() . '" in service "'. $id . '""');
                            }
                            $arguments[] = $param->getDefaultValue();
                        }
                    }
                }

                $service = $reflection->newInstance(...$arguments);

                return $this->cache[$id] = $service;
            }
            throw new ServiceNotFoundException('Undefined parameter "'.$id.'"');
        }

        $service = $this->services[$id];
        $this->cacheInstance($id, $service);

        return $this->cache[$id];
    }

    private function cacheInstance($id, $service): void
    {
        if ($service instanceof \Closure) {
            $this->cache[$id] = $service($this);
        } else {
            $this->cache[$id] = $service;
        }
    }

    public function set($id, $value): void
    {
        if (array_key_exists($id, $this->cache)) {
            unset($this->cache[$id]);
        }

        $this->services[$id] = $value;
    }

    public function has($id): bool
    {
        return array_key_exists($id, $this->services) || class_exists($id);
    }
}
