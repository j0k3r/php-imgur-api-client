<?php

namespace Imgur\tests\Middleware;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Imgur\Middleware\ErrorMiddleware;

class ErrorMiddlewareTest extends \PHPUnit_Framework_TestCase
{
    public function testNothinHappenOnOKResponse()
    {
        $mock = new MockHandler([
            new Response(200),
        ]);
        $stack = new HandlerStack($mock);
        $stack->push(ErrorMiddleware::error());

        $handler = $stack->resolve();
        $request = new Request('GET', 'http://example.com?a=b');
        $promise = $handler($request, []);
        $response = $promise->wait();

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @expectedException \Imgur\Exception\RateLimitException
     * @expectedExceptionMessage No user credits available. The limit is 10
     */
    public function testRateLimitUser()
    {
        $mock = new MockHandler([
            new Response(429, [
                'X-RateLimit-UserRemaining' => 0,
                'X-RateLimit-UserLimit' => 10,
            ]),
        ]);
        $stack = new HandlerStack($mock);
        $stack->push(ErrorMiddleware::error());

        $handler = $stack->resolve();
        $request = new Request('GET', 'http://example.com?a=b');
        $handler($request, [])->wait();
    }

    /**
     * @expectedException \Imgur\Exception\RateLimitException
     * @expectedExceptionMessage No application credits available. The limit is 10 and will be reset at 2015-09-04
     */
    public function testRateLimitClient()
    {
        $mock = new MockHandler([
            new Response(429, [
                'X-RateLimit-UserRemaining' => 9,
                'X-RateLimit-UserLimit' => 10,
                'X-RateLimit-ClientRemaining' => 0,
                'X-RateLimit-ClientLimit' => 10,
                'X-RateLimit-UserReset' => 1441401387, // 4/9/2015  23:16:27
            ]),
        ]);
        $stack = new HandlerStack($mock);
        $stack->push(ErrorMiddleware::error());

        $handler = $stack->resolve();
        $request = new Request('GET', 'http://example.com?a=b');
        $handler($request, [])->wait();
    }

    /**
     * @expectedException \Imgur\Exception\ErrorException
     * @expectedExceptionMessage Request to: /here failed with: "oops"
     */
    public function testErrorWithJson()
    {
        $mock = new MockHandler([
            new Response(429, [
                'X-RateLimit-UserRemaining' => 9,
                'X-RateLimit-UserLimit' => 10,
                'X-RateLimit-ClientRemaining' => 9,
                'X-RateLimit-ClientLimit' => 10,
            ], json_encode(['data' => ['request' => '/here', 'error' => 'oops']])),
        ]);
        $stack = new HandlerStack($mock);
        $stack->push(ErrorMiddleware::error());

        $handler = $stack->resolve();
        $request = new Request('GET', 'http://example.com?a=b');
        $handler($request, [])->wait();
    }

    /**
     * @expectedException \Imgur\Exception\RuntimeException
     * @expectedExceptionMessage hihi
     */
    public function testErrorWithoutJson()
    {
        $mock = new MockHandler([
            new Response(429, [
                'X-RateLimit-UserRemaining' => 9,
                'X-RateLimit-UserLimit' => 10,
                'X-RateLimit-ClientRemaining' => 9,
                'X-RateLimit-ClientLimit' => 10,
            ], 'hihi'),
        ]);
        $stack = new HandlerStack($mock);
        $stack->push(ErrorMiddleware::error());

        $handler = $stack->resolve();
        $request = new Request('GET', 'http://example.com?a=b');
        $handler($request, [])->wait();
    }

    /**
     * @expectedException \Imgur\Exception\RateLimitException
     * @expectedExceptionMessage No post credits available. The limit is 10 and will be reset at 2015-09-04
     */
    public function testRateLimitPost()
    {
        $mock = new MockHandler([
            new Response(429, [
                'X-Post-Rate-Limit-Remaining' => 0,
                'X-Post-Rate-Limit-Limit' => 10,
                'X-Post-Rate-Limit-Reset' => 1441401387, // 4/9/2015  23:16:27
            ]),
        ]);
        $stack = new HandlerStack($mock);
        $stack->push(ErrorMiddleware::error());

        $handler = $stack->resolve();
        $request = new Request('GET', 'http://example.com?a=b');
        $handler($request, [])->wait();
    }
}
