<?php

namespace App\Http\Action;

use App\Http\Middleware\BasicAuthActionMiddleware;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

class CabinetAction
{
    public function __invoke(ServerRequestInterface $request)
    {
        throw new \RuntimeException();

        $username = $request->getAttribute(BasicAuthActionMiddleware::ATTRIBUTE);

        return new HtmlResponse('I am logged in as ' . $username);
    }
}
