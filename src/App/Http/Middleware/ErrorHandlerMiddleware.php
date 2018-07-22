<?php

namespace App\Http\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

class ErrorHandlerMiddleware
{
    private $debug;

    public function __construct($debug = false)
    {
        $this->debug = $debug;
    }

    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        try {
            return $next($request);
        } catch (\Throwable $e) {
            if ($this->debug) {
                return new \Zend\Diactoros\Response\JsonResponse([
                    'error' => 'Server error',
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                    'trace' => $e->getTrace(),
                ], 500);
            }
            return new HtmlResponse('Server error', 500);
        }
    }
}
