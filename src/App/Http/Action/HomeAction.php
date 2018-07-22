<?php

namespace App\Http\Action;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

class HomeAction
{
    public function __invoke(ServerRequestInterface $request)
    {
        $lang = $request->getAttribute('lang');
        $name = $request->getQueryParams()['name'] ?? 'Guest';
        $contentBody = '';
        $contentBody .= 'Hello, ' . $name . PHP_EOL;
        $contentBody .= 'Your lang, ' . $lang . PHP_EOL;

        return (new HtmlResponse($contentBody))
            ->withHeader('X-Developer', 'Vitasik');
    }
}