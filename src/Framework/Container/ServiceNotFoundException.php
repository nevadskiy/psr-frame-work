<?php

namespace Framework\Container;

use Psr\Container\ContainerExceptionInterface;

class ServiceNotFoundException extends \InvalidArgumentException implements ContainerExceptionInterface
{
}