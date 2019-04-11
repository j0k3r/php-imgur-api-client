<?php

namespace Imgur\tests\Middleware;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Imgur\Middleware\AuthMiddleware;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

class AuthMiddlewareTest extends TestCase
{
    public function testDefineClientIdOnBadToken()
    {
        $token = 'token';
        $clientId = 'clientid';

        $mock = new MockHandler([
            function (RequestInterface $request, array $options) {
                $this->assertSame('Client-ID clientid', $request->getHeaderLine('Authorization'));

                return new Response(200);
            },
        ]);

        $stack = new HandlerStack($mock);
        $stack->push(Middleware::mapRequest(function (RequestInterface $request) use ($token, $clientId) {
            return (new AuthMiddleware($token, $clientId))->addAuthHeader($request);
        }));

        $handler = $stack->resolve();
        $request = new Request('GET', 'http://example.com?a=b');
        $promise = $handler($request, []);

        $this->assertInstanceOf(PromiseInterface::class, $promise);
    }

    public function testDefineBeareOnGoodToken()
    {
        $token = ['access_token' => 'T0K3N'];
        $clientId = 'clientid';

        $mock = new MockHandler([
            function (RequestInterface $request, array $options) {
                $this->assertSame('Bearer T0K3N', $request->getHeaderLine('Authorization'));

                return new Response(200);
            },
        ]);

        $stack = new HandlerStack($mock);
        $stack->push(Middleware::mapRequest(function (RequestInterface $request) use ($token, $clientId) {
            return (new AuthMiddleware($token, $clientId))->addAuthHeader($request);
        }));

        $handler = $stack->resolve();
        $request = new Request('GET', 'http://example.com?a=b');
        $promise = $handler($request, []);

        $this->assertInstanceOf(PromiseInterface::class, $promise);
    }
}
