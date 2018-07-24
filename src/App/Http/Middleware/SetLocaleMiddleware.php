<?php

namespace App\Http\Middleware;

use Psr\Http\Message\ServerRequestInterface;

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

class SetLocaleMiddleware
{
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        $request = $request->withAttribute('lang', getLang($_GET, $_COOKIE, $_SESSION, $_SERVER));

        return $next($request);
    }
}