<?php

namespace Framework\Http\Router\Route;

use Psr\Http\Message\ServerRequestInterface;
use Framework\Http\Router\Result;

/**
 * Class RegexpRoute
 * @package Framework\Http\Router
 */
class RegexpRoute implements Route
{
    private $name;
    private $pattern;
    private $handler;
    private $tokens;
    private $methods;

    public function __construct($name, $pattern, $handler, array $methods, array $tokens = [])
    {
        $this->name = $name;
        $this->pattern = $pattern;
        $this->handler = $handler;
        $this->methods = $methods;
        $this->tokens = $tokens;
    }

    public function match(ServerRequestInterface $request): ?Result
    {
        // If methods is empty - means any request method is matched.
        if ($this->methods && !\in_array($request->getMethod(), $this->methods, true)) {
            return null;
        }

        // TODO: grab to full-tested route pattern generator
        // Builds pattern like '/blog/(?P<id>\+d)
        $pattern = preg_replace_callback('~\{([^\}]+)\}~', function ($matches) {
            $argument = $matches[1]; // e.g. 'id'
            $replace = $this->tokens[$argument] ?? '[^}]+';

            return '(?P<' . $argument . '>' . $replace . ')';
        }, $this->pattern);

        $path = $request->getUri()->getPath();

        if (preg_match('~^' . $pattern . '$~i', $path, $matches)) {
            /*
             * $matches:
             * [
             *      0 => '...', // full address
             *      1 => '5', // first match
             *      'id' => '5', // first match with key naming (should be filtered to extract only that)
             * ]
             */

            return new Result(
                $this->name,
                $this->handler,
                array_filter($matches, '\is_string', ARRAY_FILTER_USE_KEY)
            );
        }

        return null;
    }

    public function generate(string $name, array $arguments = []): ?string
    {
        $arguments = array_filter($arguments);

        if ($name !== $this->name) {
            return null;
        }

        $url = preg_replace_callback('~\{([^\}]+)\}~', function($matches) use ($arguments) {
            $argument = $matches[1];

            if (!array_key_exists($argument, $arguments)) {
                throw new \InvalidArgumentException('Missing parameter "' . $argument . '"');
            }

            return $arguments[$argument];
        }, $this->pattern);

        return $url;
    }
}
