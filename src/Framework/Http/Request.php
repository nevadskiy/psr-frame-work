<?php

namespace Framework\Http;

class Request
{
    public function getQueryParams(): array
    {
        return $_GET;
    }

    public function getCookies(): array
    {
        return $_COOKIE;
    }

    public function getParsedBody()
    {
        return $_POST ?: null;
    }

    /**
     * If input stream is XML or JSON or something like that,
     * we should parse it with json_encode or others.
     *
     * @return bool|string
     */
    public function getBody()
    {
        return file_get_contents('php://input');
    }
}