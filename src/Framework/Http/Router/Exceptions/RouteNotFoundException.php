<?php

namespace Framework\Http\Router\Exceptions;

class RouteNotFoundException extends \LogicException
{
    private $name;
    private $attributes;

    public function __construct(string $name, array $attributes)
    {
        parent::__construct('Route "' . $name . '" not found.');

        $this->name = $name;
        $this->attributes = $attributes;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
