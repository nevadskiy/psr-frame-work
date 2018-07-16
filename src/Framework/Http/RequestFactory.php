<?php

namespace Framework\Http;

class RequestFactory
{
    /**
     * Make request from globals arrays
     *
     * @param array|null $query
     * @param array|null $body
     * @return Request
     */
    public static function fromGlobals(array $query = null, array $body = null): Request
    {
        return (new Request())
            ->withQueryParams($query ?: $_GET)
            ->withParsedBody($body ?: $_POST);
    }
}