<?php

namespace Imgur\Tests\HttpClient;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Subscriber\Mock;
use Imgur\HttpClient\HttpClient;

class HttpClientTest extends \PHPUnit_Framework_TestCase
{
    public function testOptionsToConstructor()
    {
        $httpClient = new TestHttpClient([
            'headers' => ['Cache-Control' => 'no-cache'],
        ]);

        $this->assertSame(['Cache-Control' => 'no-cache'], $httpClient->getOption('headers'));
        $this->assertNull($httpClient->getOption('base_url'));
    }

    public function testDoGETRequest()
    {
        $path = '/some/path';
        $parameters = ['a' => 'b'];

        $client = new GuzzleClient();
        $mock = new Mock([
            new Response(200, ['Content-Type' => 'application/json'], Stream::factory(json_encode(['data' => 'ok !']))),
        ]);
        $client->getEmitter()->attach($mock);

        $httpClient = new HttpClient([], $client);
        $response = $httpClient->get($path, $parameters);

        $result = $httpClient->parseResponse($response);

        $this->assertSame('ok !', $result);
    }

    public function testDoPOSTRequest()
    {
        $path = '/some/path';
        $parameters = ['a' => 'b'];

        $client = new GuzzleClient();
        $mock = new Mock([
            new Response(200, ['Content-Type' => 'application/json'], Stream::factory(json_encode(['data' => 'ok !']))),
        ]);
        $client->getEmitter()->attach($mock);

        $httpClient = new HttpClient([], $client);
        $response = $httpClient->post($path, $parameters);

        $result = $httpClient->parseResponse($response);

        $this->assertSame('ok !', $result);
    }

    public function testDoPUTRequest()
    {
        $path = '/some/path';
        $parameters = ['a' => 'b'];

        $client = new GuzzleClient();
        $mock = new Mock([
            new Response(200, ['Content-Type' => 'application/json'], Stream::factory(json_encode(['data' => 'ok !']))),
        ]);
        $client->getEmitter()->attach($mock);

        $httpClient = new HttpClient([], $client);
        $response = $httpClient->put($path, $parameters);

        $result = $httpClient->parseResponse($response);

        $this->assertSame('ok !', $result);
    }

    public function testDoDELETERequest()
    {
        $path = '/some/path';
        $parameters = ['a' => 'b'];

        $client = new GuzzleClient();
        $mock = new Mock([
            new Response(200, ['Content-Type' => 'application/json'], Stream::factory(json_encode(['data' => 'ok !']))),
        ]);
        $client->getEmitter()->attach($mock);

        $httpClient = new HttpClient([], $client);
        $response = $httpClient->delete($path, $parameters);

        $result = $httpClient->parseResponse($response);

        $this->assertSame('ok !', $result);
    }

    public function testDoCustomRequest()
    {
        $path = '/some/path';
        $options = ['c' => 'd'];

        $client = new GuzzleClient();
        $mock = new Mock([
            new Response(200, ['Content-Type' => 'application/json'], Stream::factory(json_encode(['data' => true]))),
        ]);
        $client->getEmitter()->attach($mock);

        $httpClient = new HttpClient([], $client);
        $response = $httpClient->performRequest($path, $options, 'HEAD');

        $result = $httpClient->parseResponse($response);

        $this->assertTrue($result);
    }

    /**
     * @expectedException \Imgur\Exception\RateLimitException
     * @expectedExceptionMessage No user credits available. The limit is 10
     */
    public function testThrowExceptionWhenApiIsExceeded()
    {
        $path = '/some/path';
        $parameters = ['a' => 'b'];

        $response = new Response(429);
        $response->addHeader('X-RateLimit-UserLimit', 10);
        $response->addHeader('X-RateLimit-UserRemaining', 0);

        $client = new GuzzleClient();
        $mock = new Mock([$response]);
        $client->getEmitter()->attach($mock);

        $httpClient = new TestHttpClient([], $client);
        $httpClient->get($path, $parameters);
    }

    /**
     * @expectedException \Imgur\Exception\RateLimitException
     * @expectedExceptionMessage No application credits available. The limit is 10 and will be reset at
     */
    public function testThrowExceptionWhenClientApiIsExceeded()
    {
        $path = '/some/path';
        $parameters = ['a' => 'b'];

        $response = new Response(429);
        $response->addHeader('X-RateLimit-UserLimit', 10);
        $response->addHeader('X-RateLimit-UserRemaining', 10);
        $response->addHeader('X-RateLimit-ClientLimit', 10);
        $response->addHeader('X-RateLimit-ClientRemaining', 0);
        $response->addHeader('X-RateLimit-UserReset', 1474318026);

        $client = new GuzzleClient();
        $mock = new Mock([$response]);
        $client->getEmitter()->attach($mock);

        $httpClient = new TestHttpClient([], $client);
        $httpClient->get($path, $parameters);
    }

    /**
     * @expectedException \Imgur\Exception\RuntimeException
     * @expectedExceptionMessage oops
     */
    public function testThrowExceptionWhenBadRequestPlainError()
    {
        $path = '/some/path';
        $parameters = ['a' => 'b'];

        $response = new Response(429, [], Stream::factory('oops'));
        $response->addHeader('X-RateLimit-UserLimit', 10);
        $response->addHeader('X-RateLimit-UserRemaining', 10);
        $response->addHeader('X-RateLimit-ClientLimit', 10);
        $response->addHeader('X-RateLimit-ClientRemaining', 10);

        $client = new GuzzleClient();
        $mock = new Mock([$response]);
        $client->getEmitter()->attach($mock);

        $httpClient = new TestHttpClient([], $client);
        $httpClient->get($path, $parameters);
    }

    /**
     * @expectedException \Imgur\Exception\ErrorException
     * @expectedExceptionMessage Request to: /3/account failed with: "oops2"
     */
    public function testThrowExceptionWhenBadRequestJsonError()
    {
        $path = '/some/path';
        $parameters = ['a' => 'b'];

        $response = new Response(429, [], Stream::factory(json_encode(['data' => ['request' => '/3/account', 'error' => 'oops2', 'method' => 'GET'], 'success' => false, 'status' => 429])));
        $response->addHeader('X-RateLimit-UserLimit', 10);
        $response->addHeader('X-RateLimit-UserRemaining', 10);
        $response->addHeader('X-RateLimit-ClientLimit', 10);
        $response->addHeader('X-RateLimit-ClientRemaining', 10);

        $client = new GuzzleClient();
        $mock = new Mock([$response]);
        $client->getEmitter()->attach($mock);

        $httpClient = new TestHttpClient([], $client);
        $httpClient->get($path, $parameters);
    }

    /**
     * @expectedException \Imgur\Exception\RuntimeException
     * @expectedExceptionMessage oops
     */
    public function testThrowExceptionWhenBadRequestNoClientMock()
    {
        $path = '/some/path';
        $parameters = ['a' => 'b'];

        $response = new Response(429, [], Stream::factory('oops'));
        $response->addHeader('X-RateLimit-UserLimit', 10);
        $response->addHeader('X-RateLimit-UserRemaining', 10);
        $response->addHeader('X-RateLimit-ClientLimit', 10);
        $response->addHeader('X-RateLimit-ClientRemaining', 10);

        $client = new GuzzleClient();
        $mock = new Mock([$response]);
        $client->getEmitter()->attach($mock);

        $httpClient = new HttpClient([], $client);
        $httpClient->get($path, $parameters);
    }

    /**
     * @expectedException \Imgur\Exception\ErrorException
     */
    public function testThrowLogicException()
    {
        $path = '/some/path';
        $parameters = ['a = b'];

        $response = new Response(200, [], Stream::factory('data'));

        $client = $this->getMockBuilder('GuzzleHttp\Client')
            ->disableOriginalConstructor()
            ->getMock();

        $client->expects($this->any())
            ->method('getEmitter')
            ->willReturn(new \GuzzleHttp\Event\Emitter());

        $request = $this->getMockBuilder('GuzzleHttp\Message\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $client->expects($this->any())
            ->method('createRequest')
            ->willReturn($request);

        $client->expects($this->any())
            ->method('send')
            ->will($this->throwException(new \LogicException()));

        $httpClient = new HttpClient([], $client);
        $httpClient->get($path, $parameters);
    }
}

class TestHttpClient extends HttpClient
{
    public function getOption($name, $default = null)
    {
        return isset($this->options[$name]) ? $this->options[$name] : $default;
    }
}
