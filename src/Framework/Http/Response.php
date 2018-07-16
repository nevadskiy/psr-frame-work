<?php

namespace Framework\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Class Response
 * @package Framework\Http
 */
class Response implements ResponseInterface
{
    /**
     * @var array
     */
    protected $headers = [];
    /**
     * @var
     */
    protected $body;
    /**
     * @var int
     */
    protected $statusCode;
    /**
     * @var string
     */
    protected $reasonPhrase = '';

    /**
     * @var array
     */
    protected static $phrases = [
        200 => 'OK',
        301 => 'Moved Permanently',
        400 => 'Bad Request',
        403 => 'Forbidden',
        404 => 'Not Found',
        500 => 'Internal Server Error',
    ];

    /**
     * Response constructor.
     * @param $body
     * @param int $status
     */
    public function __construct($body = '', $status = 200)
    {
        $this->body = $body instanceof StreamInterface ? $body : new Stream($body);
        $this->statusCode = $status;
    }

    /**
     * @return mixed
     */
    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    /**
     * @param StreamInterface $body
     * @return Response
     */
    public function withBody(StreamInterface $body): Response
    {
        $clone = clone $this;
        $clone->body = $body;

        return $clone;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return mixed|string
     */
    public function getReasonPhrase()
    {
        if (!$this->reasonPhrase && isset(self::$phrases[$this->statusCode])) {
            $this->reasonPhrase = self::$phrases[$this->statusCode];
        }

        return $this->reasonPhrase;
    }

    /**
     * @param $code
     * @param string $reasonPhrase
     * @return Response
     */
    public function withStatus($code, $reasonPhrase = ''): Response
    {
        $clone = clone $this;
        $clone->statusCode = $code;
        $clone->reasonPhrase = $reasonPhrase;

        return $clone;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param $header
     * @return bool
     */
    public function hasHeader($header): bool
    {
        return isset($this->headers[$header]);
    }

    /**
     * @param $header
     * @return mixed|null
     */
    public function getHeader($header)
    {
        if (!$this->hasHeader($header)) {
            return null;
        }

        return $this->headers[$header];
    }

    /**
     * @param $header
     * @param $value
     * @return Response
     */
    public function withHeader($header, $value): Response
    {
        $clone = clone $this;
        if ($clone->hasHeader($header)) {
            unset($clone->headers[$header]);
        }

        $clone->headers[$header] = $value;

        return $clone;
    }

    /**
     * Below are not supported
     */
    public function getProtocolVersion() {}
    public function withProtocolVersion($version) {}
    public function getHeaderLine($name) {}
    public function withAddedHeader($name, $value) {}
    public function withoutHeader($name) {}
}
