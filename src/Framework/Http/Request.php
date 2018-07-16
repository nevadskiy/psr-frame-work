<?php

namespace Framework\Http;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

/**
 * Class Request
 */
class Request implements ServerRequestInterface
{
    /**
     * @var array
     */
    protected $queryParams = [];
    /**
     * @var null|array
     */
    protected $parsedBody;

    /**
     * Request constructor.
     * @param array $queryParams
     * @param array $parsedBody
     */
    public function __construct(array $queryParams = [], array $parsedBody = null)
    {
        $this->queryParams = $queryParams;
        $this->parsedBody = $parsedBody;
    }

    /**
     * Clone query params to request copy
     * Immutable object pattern.
     *
     * @param array $queryParams
     * @return Request
     */
    public function withQueryParams(array $queryParams): Request
    {
        // $queryParams available inside self class
        // even with private scope

        $clone = clone $this;
        $clone->queryParams = $queryParams;

        return $clone;
    }

    /**
     * Clone parsed body to request copy
     * Immutable object pattern.
     *
     * @param array|null|object $data
     * @return Request
     */
    public function withParsedBody($data): Request
    {
        // $parsedBody available inside self class
        // even with private scope

        $clone = clone $this;
        $clone->parsedBody = $data;

        return $clone;
    }

    /**
     * @return array
     */
    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    /**
     * @return array|null
     */
    public function getParsedBody(): ?array
    {
        return $this->parsedBody;
    }

    /**
     * Below are not supported.
     */
    public function getProtocolVersion() {}
    public function withProtocolVersion($version) {}
    public function getHeaders() {}
    public function hasHeader($name) {}
    public function getHeader($name) {}
    public function getHeaderLine($name) {}
    public function withHeader($name, $value) {}
    public function withAddedHeader($name, $value) {}
    public function withoutHeader($name) {}
    public function getBody() {}
    public function withBody(StreamInterface $body) {}
    public function getRequestTarget() {}
    public function withRequestTarget($requestTarget) {}
    public function getMethod() {}
    public function withMethod($method) {}
    public function getUri() {}
    public function withUri(UriInterface $uri, $preserveHost = false) {}
    public function getServerParams() {}
    public function getCookieParams() {}
    public function withCookieParams(array $cookies) {}
    public function getUploadedFiles() {}
    public function withUploadedFiles(array $uploadedFiles) {}
    public function getAttributes() {}
    public function getAttribute($name, $default = null) {}
    public function withAttribute($name, $value) {}
    public function withoutAttribute($name) {}
}