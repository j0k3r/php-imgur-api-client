<?php

namespace Imgur\tests\HttpClient;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Imgur\HttpClient\HttpClient;
use PHPUnit\Framework\TestCase;

class HttpClientTest extends TestCase
{
    public function testOptionsToConstructor()
    {
        $httpClient = new TestHttpClient([
            'headers' => ['Cache-Control' => 'no-cache'],
        ]);

        $this->assertSame(['Cache-Control' => 'no-cache'], $httpClient->getOption('headers'));
        $this->assertNull($httpClient->getOption('base_uri'));
    }

    public function testDoGETRequest()
    {
        $path = '/some/path';
        $parameters = ['a' => 'b'];

        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], json_encode(['data' => 'ok !'])),
        ]);
        $handler = HandlerStack::create($mock);
        $client = new GuzzleClient(['handler' => $handler]);

        $httpClient = new HttpClient([], $client);
        $response = $httpClient->get($path, $parameters);

        $result = $httpClient->parseResponse($response);

        $this->assertSame('ok !', $result);
    }

    public function testDoPOSTRequest()
    {
        $path = '/some/path';
        $parameters = ['a' => 'b'];

        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], json_encode(['data' => 'ok !'])),
        ]);
        $handler = HandlerStack::create($mock);
        $client = new GuzzleClient(['handler' => $handler]);

        $httpClient = new HttpClient([], $client);
        $response = $httpClient->post($path, $parameters);

        $result = $httpClient->parseResponse($response);

        $this->assertSame('ok !', $result);
    }

    public function testDoPOSTRequestWithMultipart()
    {
        $path = '/some/path';
        $parameters = [
            'a' => 'b',
            'type' => 'file',
        ];

        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], json_encode(['data' => 'ok !'])),
        ]);
        $handler = HandlerStack::create($mock);
        $client = new GuzzleClient(['handler' => $handler]);

        $httpClient = new HttpClient([], $client);
        $response = $httpClient->post($path, $parameters);

        $result = $httpClient->parseResponse($response);

        $this->assertSame('ok !', $result);
    }

    public function testDoPUTRequest()
    {
        $path = '/some/path';
        $parameters = ['a' => 'b'];

        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], json_encode(['data' => 'ok !'])),
        ]);
        $handler = HandlerStack::create($mock);
        $client = new GuzzleClient(['handler' => $handler]);

        $httpClient = new HttpClient([], $client);
        $response = $httpClient->put($path, $parameters);

        $result = $httpClient->parseResponse($response);

        $this->assertSame('ok !', $result);
    }

    public function testDoDELETERequest()
    {
        $path = '/some/path';
        $parameters = ['a' => 'b'];

        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], json_encode(['data' => 'ok !'])),
        ]);
        $handler = HandlerStack::create($mock);
        $client = new GuzzleClient(['handler' => $handler]);

        $httpClient = new HttpClient([], $client);
        $response = $httpClient->delete($path, $parameters);

        $result = $httpClient->parseResponse($response);

        $this->assertSame('ok !', $result);
    }

    public function testDoCustomRequest()
    {
        $path = '/some/path';
        $options = ['c' => 'd'];

        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], json_encode(['data' => true])),
        ]);
        $handler = HandlerStack::create($mock);
        $client = new GuzzleClient(['handler' => $handler]);

        $httpClient = new HttpClient([], $client);
        $response = $httpClient->performRequest($path, $options, 'HEAD');

        $result = $httpClient->parseResponse($response);

        $this->assertTrue($result);
    }

    public function testThrowExceptionWhenApiIsExceeded()
    {
        $this->expectException(\Imgur\Exception\RateLimitException::class);
        $this->expectExceptionMessage('No user credits available. The limit is 10');

        $path = '/some/path';
        $parameters = ['a' => 'b'];

        $mock = new MockHandler([
            new Response(429, [
                'X-RateLimit-UserLimit' => 10,
                'X-RateLimit-UserRemaining' => 0,
            ]),
        ]);
        $handler = HandlerStack::create($mock);
        $client = new GuzzleClient(['handler' => $handler]);

        $httpClient = new TestHttpClient([], $client);
        $httpClient->get($path, $parameters);
    }

    public function testThrowExceptionWhenClientApiIsExceeded()
    {
        $this->expectException(\Imgur\Exception\RateLimitException::class);
        $this->expectExceptionMessage('No application credits available. The limit is 10 and will be reset at');

        $path = '/some/path';
        $parameters = ['a' => 'b'];

        $response = new Response(429, [
            'X-RateLimit-UserLimit' => 10,
            'X-RateLimit-UserRemaining' => 10,
            'X-RateLimit-ClientLimit' => 10,
            'X-RateLimit-ClientRemaining' => 0,
            'X-RateLimit-UserReset' => 1474318026,
        ]);

        $mock = new MockHandler([$response]);
        $handler = HandlerStack::create($mock);
        $client = new GuzzleClient(['handler' => $handler]);

        $httpClient = new TestHttpClient([], $client);
        $httpClient->get($path, $parameters);
    }

    public function testThrowExceptionWhenBadRequestPlainError()
    {
        $this->expectException(\Imgur\Exception\RuntimeException::class);
        $this->expectExceptionMessage('oops');

        $path = '/some/path';
        $parameters = ['a' => 'b'];

        $response = new Response(429, [
            'X-RateLimit-UserLimit' => 10,
            'X-RateLimit-UserRemaining' => 10,
            'X-RateLimit-ClientLimit' => 10,
            'X-RateLimit-ClientRemaining' => 10,
        ], 'oops');

        $mock = new MockHandler([$response]);
        $handler = HandlerStack::create($mock);
        $client = new GuzzleClient(['handler' => $handler]);

        $httpClient = new TestHttpClient([], $client);
        $httpClient->get($path, $parameters);
    }

    public function testThrowExceptionWhenBadRequestJsonError()
    {
        $this->expectException(\Imgur\Exception\ErrorException::class);
        $this->expectExceptionMessage('Request to: /3/account failed with: "oops2"');

        $path = '/some/path';
        $parameters = ['a' => 'b'];

        $response = new Response(429, [
            'X-RateLimit-UserLimit' => 10,
            'X-RateLimit-UserRemaining' => 10,
            'X-RateLimit-ClientLimit' => 10,
            'X-RateLimit-ClientRemaining' => 10,
        ], json_encode(['data' => ['request' => '/3/account', 'error' => 'oops2', 'method' => 'GET'], 'success' => false, 'status' => 403]));

        $mock = new MockHandler([$response]);
        $handler = HandlerStack::create($mock);
        $client = new GuzzleClient(['handler' => $handler]);

        $httpClient = new TestHttpClient([], $client);
        $httpClient->get($path, $parameters);
    }

    public function testThrowExceptionWhenBadRequestNoClientMock()
    {
        $this->expectException(\Imgur\Exception\RuntimeException::class);
        $this->expectExceptionMessage('oops');

        $path = '/some/path';
        $parameters = ['a' => 'b'];

        $response = new Response(429, [
            'X-RateLimit-UserLimit' => 10,
            'X-RateLimit-UserRemaining' => 10,
            'X-RateLimit-ClientLimit' => 10,
            'X-RateLimit-ClientRemaining' => 10,
        ], 'oops');

        $mock = new MockHandler([$response]);
        $handler = HandlerStack::create($mock);
        $client = new GuzzleClient(['handler' => $handler]);

        $httpClient = new HttpClient([], $client);
        $httpClient->get($path, $parameters);
    }

    // public function testThrowLogicException()
    // {
    //     $this->expectException(\Imgur\Exception\ErrorException::class);
    //
    //     $path = '/some/path';
    //     $parameters = ['a = b'];

    //     $response = new Response(200, [], 'data');

    //     $client = $this->getMockBuilder('GuzzleHttp\Client')
    //         ->disableOriginalConstructor()
    //         ->getMock();

    //     $client->expects($this->any())
    //         ->method('getEmitter')
    //         ->willReturn(new \GuzzleHttp\Event\Emitter());

    //     $request = $this->getMockBuilder('GuzzleHttp\Message\Request')
    //         ->disableOriginalConstructor()
    //         ->getMock();

    //     $client->expects($this->any())
    //         ->method('createRequest')
    //         ->willReturn($request);

    //     $client->expects($this->any())
    //         ->method('send')
    //         ->will($this->throwException(new \LogicException()));

    //     $httpClient = new HttpClient([], $client);
    //     $httpClient->get($path, $parameters);
    // }
}

class TestHttpClient extends HttpClient
{
    public function getOption($name, $default = null)
    {
        return isset($this->options[$name]) ? $this->options[$name] : $default;
    }
}
