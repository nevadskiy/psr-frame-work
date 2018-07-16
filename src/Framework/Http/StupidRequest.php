<?php

namespace Framework\Http;

class StupidRequest
{
    /**
     * Get query parameters
     * Has problem when two or more instances of $this are created
     * All instances would be have the same results
     * because of using super global $_GET array
     * (The same like singletons anti-pattern issues)
     *
     * @return array
     */
    public function getQueryParams(): array
    {
        return $_GET;
    }

    /**
     * Get request cookies
     *
     * @return array
     */
    public function getCookies(): array
    {
        return $_COOKIE;
    }

    /**
     * Get parsed input body parameters
     *
     * @return array|null
     */
    public function getParsedBody(): ?array
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