<?php

namespace Imgur\tests\Middleware;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Imgur\Exception\ErrorException;
use Imgur\Exception\RateLimitException;
use Imgur\Middleware\ErrorMiddleware;
use PHPUnit\Framework\TestCase;

class ErrorMiddlewareTest extends TestCase
{
    public function testNothinHappenOnOKResponse(): void
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

        $this->assertSame(200, $response->getStatusCode());
    }

    public function testRateLimitUser(): void
    {
        $this->expectException(RateLimitException::class);
        $this->expectExceptionMessage('No user credits available. The limit is 10');

        $mock = new MockHandler([
            new Response(429, [
                'X-RateLimit-UserRemaining' => '0',
                'X-RateLimit-UserLimit' => '10',
            ]),
        ]);
        $stack = new HandlerStack($mock);
        $stack->push(ErrorMiddleware::error());

        $handler = $stack->resolve();
        $request = new Request('GET', 'http://example.com?a=b');
        $handler($request, [])->wait();
    }

    public function testRateLimitClient(): void
    {
        $this->expectException(RateLimitException::class);
        $this->expectExceptionMessage('No application credits available. The limit is 10 and will be reset at 2015-09-04');

        $mock = new MockHandler([
            new Response(429, [
                'X-RateLimit-UserRemaining' => '9',
                'X-RateLimit-UserLimit' => '10',
                'X-RateLimit-ClientRemaining' => '0',
                'X-RateLimit-ClientLimit' => '10',
                'X-RateLimit-UserReset' => '1441401387', // 4/9/2015  23:16:27
            ]),
        ]);
        $stack = new HandlerStack($mock);
        $stack->push(ErrorMiddleware::error());

        $handler = $stack->resolve();
        $request = new Request('GET', 'http://example.com?a=b');
        $handler($request, [])->wait();
    }

    public function testErrorOverCapacity(): void
    {
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('Request failed with: "Imgur is temporarily over capacity. Please try again later."');

        $mock = new MockHandler([
            new Response(429, [
                'X-RateLimit-UserRemaining' => '9',
                'X-RateLimit-UserLimit' => '10',
                'X-RateLimit-ClientRemaining' => '9',
                'X-RateLimit-ClientLimit' => '10',
            ], (string) json_encode(['status' => 500, 'success' => false, 'data' => ['error' => 'Imgur is temporarily over capacity. Please try again later.']])),
        ]);
        $stack = new HandlerStack($mock);
        $stack->push(ErrorMiddleware::error());

        $handler = $stack->resolve();
        $request = new Request('GET', 'http://example.com?a=b');
        $handler($request, [])->wait();
    }

    public function testErrorUploadingTooFast(): void
    {
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('Request to: /3/image.json failed with: "You are uploading too fast. Please wait 59 more minutes."');

        $mock = new MockHandler([
            new Response(429, [
                'X-RateLimit-UserRemaining' => '9',
                'X-RateLimit-UserLimit' => '10',
                'X-RateLimit-ClientRemaining' => '9',
                'X-RateLimit-ClientLimit' => '10',
            ], (string) json_encode(['status' => 400, 'success' => false, 'data' => ['error' => ['code' => 429, 'message' => 'You are uploading too fast. Please wait 59 more minutes.', 'type' => 'ImgurException', 'exception' => []], 'request' => '/3/image.json', 'method' => 'POST']])),
        ]);
        $stack = new HandlerStack($mock);
        $stack->push(ErrorMiddleware::error());

        $handler = $stack->resolve();
        $request = new Request('GET', 'http://example.com?a=b');
        $handler($request, [])->wait();
    }

    public function testErrorNoMessageInError(): void
    {
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('Request to: /3/image.json failed with: "Error code: 666"');

        $mock = new MockHandler([
            new Response(429, [
                'X-RateLimit-UserRemaining' => '9',
                'X-RateLimit-UserLimit' => '10',
                'X-RateLimit-ClientRemaining' => '9',
                'X-RateLimit-ClientLimit' => '10',
            ], (string) json_encode(['status' => 400, 'success' => false, 'data' => ['error' => ['code' => 666, 'type' => 'ImgurException', 'exception' => []], 'request' => '/3/image.json', 'method' => 'POST']])),
        ]);
        $stack = new HandlerStack($mock);
        $stack->push(ErrorMiddleware::error());

        $handler = $stack->resolve();
        $request = new Request('GET', 'http://example.com?a=b');
        $handler($request, [])->wait();
    }

    public function testErrorWithJson(): void
    {
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('Request to: /here failed with: "oops"');

        $mock = new MockHandler([
            new Response(429, [
                'X-RateLimit-UserRemaining' => '9',
                'X-RateLimit-UserLimit' => '10',
                'X-RateLimit-ClientRemaining' => '9',
                'X-RateLimit-ClientLimit' => '10',
            ], (string) json_encode(['data' => ['request' => '/here', 'error' => 'oops']])),
        ]);
        $stack = new HandlerStack($mock);
        $stack->push(ErrorMiddleware::error());

        $handler = $stack->resolve();
        $request = new Request('GET', 'http://example.com?a=b');
        $handler($request, [])->wait();
    }

    public function testErrorWithoutJson(): void
    {
        $this->expectException(\Imgur\Exception\RuntimeException::class);
        $this->expectExceptionMessage('hihi');

        $mock = new MockHandler([
            new Response(429, [
                'X-RateLimit-UserRemaining' => '9',
                'X-RateLimit-UserLimit' => '10',
                'X-RateLimit-ClientRemaining' => '9',
                'X-RateLimit-ClientLimit' => '10',
            ], 'hihi'),
        ]);
        $stack = new HandlerStack($mock);
        $stack->push(ErrorMiddleware::error());

        $handler = $stack->resolve();
        $request = new Request('GET', 'http://example.com?a=b');
        $handler($request, [])->wait();
    }

    public function testRateLimitPost(): void
    {
        $this->expectException(RateLimitException::class);
        $this->expectExceptionMessage('No post credits available. The limit is 10 and will be reset at 2015-09-04');

        $mock = new MockHandler([
            new Response(429, [
                'X-Post-Rate-Limit-Remaining' => '0',
                'X-Post-Rate-Limit-Limit' => '10',
                'X-Post-Rate-Limit-Reset' => '1441401387', // 4/9/2015  23:16:27
            ]),
        ]);
        $stack = new HandlerStack($mock);
        $stack->push(ErrorMiddleware::error());

        $handler = $stack->resolve();
        $request = new Request('GET', 'http://example.com?a=b');
        $handler($request, [])->wait();
    }
}
