<?php

use Framework\Http\Request;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Debug section
|--------------------------------------------------------------------------
*/
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function getLang(array $get, array $cookie, array $session, array $server, $default = 'en') {
    if (!empty($get['lang'])) {
        return $get['lang'];
    }

    if (!empty($cookie['lang'])) {
        return $cookie['lang'];
    }

    if (!empty($session['lang'])) {
        return $session['lang'];
    }

    if (!empty($server['HTTP_ACCEPT_LANGUAGE'])) {
        return substr($server['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    }

    return $default;
}

session_start();

$request = new Request();

//$request->getMethod();
//$request->getBody();

$name = $request->getQueryParams()['name'] ?? 'Guest';

$lang = getLang($_GET, $_COOKIE, $_SESSION, $_SERVER);

header('X-Developer: Vitasik');

echo 'Hello, ' . $name . PHP_EOL;
echo 'Your lang, ' . $lang . PHP_EOL;