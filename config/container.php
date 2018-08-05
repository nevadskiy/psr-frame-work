<?php

use Framework\Container\Container;

$container = new Container();
$container->set('config', require 'params.php');
require 'app.php';

return $container;