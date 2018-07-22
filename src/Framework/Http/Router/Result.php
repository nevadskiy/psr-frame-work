<?php

namespace Framework\Http\Router;

class Result
{
    private $name;
    private $handler;
    private $attributes;

    /**
     * Result constructor.
     * @param $name
     * @param $handler
     * @param $attributes
     */
    public function __construct($name, $handler, array $attributes)
    {
        $this->name = $name;
        $this->handler = $handler;
        $this->attributes = $attributes;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getHandler()
    {
        return $this->handler;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
