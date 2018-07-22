<?php

namespace App\Http\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\EmptyResponse;

class BasicAuthActionMiddleware
{
    public const ATTRIBUTE = '_user';

    private $users;

    public function __construct(array $users)
    {
        $this->users = $users;
    }

    /**
     * USAGE:
     * $routes->get('profile', new BasicAuthActionDecorator(new Profile, $users));
     *
     * @param ServerRequestInterface $request
     * @param callable $next
     * @return EmptyResponse
     */
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        $username = $request->getServerParams()['PHP_AUTH_USER'] ?? null;
        $password = $request->getServerParams()['PHP_AUTH_PW'] ?? null;

        if (!empty($username) && !empty($password)) {
            foreach ($this->users as $name => $pass) {
                if ($username === $name && $password === $pass) {
                    return $next($request->withAttribute(self::ATTRIBUTE, $username));
                }
            }
        }

        return new EmptyResponse(401, ['WWW-Authenticate' => 'Basic realm=Restricted area']);
    }
}
