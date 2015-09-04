<?php

namespace Imgur\Tests\HttpClient;

use Imgur\Client;
use Imgur\HttpClient\HttpClient;
use Guzzle\Http\Message\Response;
use Guzzle\Plugin\Mock\MockPlugin;
use Guzzle\Http\Client as GuzzleClient;

class HttpClientTest extends \PHPUnit_Framework_TestCase
{
    public function testOptionsToConstructor()
    {
        $httpClient = new TestHttpClient(array(
            'headers' => array('Cache-Control' => 'no-cache'),
        ));

        $this->assertEquals(array('Cache-Control' => 'no-cache'), $httpClient->getOption('headers'));
        $this->assertEquals('https://api.imgur.com/3/', $httpClient->getOption('base_url'));
    }

    public function testDoGETRequest()
    {
        $path = '/some/path';
        $parameters = array('a' => 'b');

        $client = $this->getClientMock();
        $client->expects($this->any())
            ->method('send')
            ->will($this->returnValue(new Response(200, null, json_encode(array('data' => 'ok !')))));

        $httpClient = new HttpClient(array(), $client);
        $response = $httpClient->get($path, $parameters);

        $result = $httpClient->parseResponse($response);

        $this->assertEquals(array('data' => 'ok !'), $result);
    }

    public function testDoPOSTRequest()
    {
        $path = '/some/path';
        $parameters = array('a' => 'b');

        $client = $this->getClientMock();
        $client->expects($this->once())
            ->method('createRequest')
            ->with('POST', $path, array(), $parameters);

        $httpClient = new HttpClient(array(), $client);
        $httpClient->post($path, $parameters);
    }

    public function testDoPOSTRequestWithoutContent()
    {
        $path = '/some/path';

        $client = $this->getClientMock();
        $client->expects($this->once())
            ->method('createRequest')
            ->with('POST', $path, $this->isType('array'));

        $httpClient = new HttpClient(array(), $client);
        $response = $httpClient->post($path);

        $result = $httpClient->parseResponse($response);

        // default result
        $this->assertEquals(array('data' => array(), 'success' => false), $result);
    }

    public function testDoDELETERequest()
    {
        $path = '/some/path';
        $parameters = array('a' => 'b');

        $client = $this->getClientMock();

        $httpClient = new HttpClient(array(), $client);
        $httpClient->delete($path, $parameters);
    }

    public function testDoCustomRequest()
    {
        $path = '/some/path';
        $options = array('c' => 'd');

        $client = $this->getClientMock();

        $httpClient = new HttpClient(array(), $client);
        $httpClient->performRequest($path, $options, 'HEAD');
    }

    public function testAllowToReturnRawContent()
    {
        $path = '/some/path';
        $parameters = array('a' => 'b');

        $message = $this->getMock('Guzzle\Http\Message\Response', array(), array(200));
        $message->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue('Just raw context'));

        $client = $this->getClientMock();
        $client->expects($this->once())
            ->method('send')
            ->will($this->returnValue($message));

        $httpClient = new HttpClient(array(), $client);
        $response = $httpClient->get($path, $parameters);

        $this->assertEquals('Just raw context', $response->getBody());
        $this->assertInstanceOf('Guzzle\Http\Message\MessageInterface', $response);
    }

    /**
     * @expectedException \Imgur\Exception\RateLimitException
     * @expectedExceptionMessage No user credits available. The limit is 10
     */
    public function testThrowExceptionWhenApiIsExceeded()
    {
        $path = '/some/path';
        $parameters = array('a' => 'b');

        $response = new Response(403);
        $response->addHeader('X-RateLimit-UserLimit', 10);
        $response->addHeader('X-RateLimit-UserRemaining', 0);

        $mockPlugin = new MockPlugin();
        $mockPlugin->addResponse($response);

        $client = new GuzzleClient('http://123.com/');
        $client->addSubscriber($mockPlugin);

        $httpClient = new TestHttpClient(array(), $client);
        $httpClient->get($path, $parameters);
    }

    /**
     * @expectedException \Imgur\Exception\RateLimitException
     * @expectedExceptionMessage No application credits available. The limit is 10 and will be reset at 1970-01-01
     */
    public function testThrowExceptionWhenClientApiIsExceeded()
    {
        $path = '/some/path';
        $parameters = array('a' => 'b');

        $response = new Response(403);
        $response->addHeader('X-RateLimit-UserLimit', 10);
        $response->addHeader('X-RateLimit-UserRemaining', 10);
        $response->addHeader('X-RateLimit-ClientLimit', 10);
        $response->addHeader('X-RateLimit-ClientRemaining', 0);

        $mockPlugin = new MockPlugin();
        $mockPlugin->addResponse($response);

        $client = new GuzzleClient('http://123.com/');
        $client->addSubscriber($mockPlugin);

        $httpClient = new TestHttpClient(array(), $client);
        $httpClient->get($path, $parameters);
    }

    /**
     * @expectedException \Imgur\Exception\RuntimeException
     * @expectedExceptionMessage oops
     */
    public function testThrowExceptionWhenBadRequestPlainError()
    {
        $path = '/some/path';
        $parameters = array('a' => 'b');

        $response = new Response(403, null, 'oops');
        $response->addHeader('X-RateLimit-UserLimit', 10);
        $response->addHeader('X-RateLimit-UserRemaining', 10);
        $response->addHeader('X-RateLimit-ClientLimit', 10);
        $response->addHeader('X-RateLimit-ClientRemaining', 10);

        $mockPlugin = new MockPlugin();
        $mockPlugin->addResponse($response);

        $client = new GuzzleClient('http://123.com/');
        $client->addSubscriber($mockPlugin);

        $httpClient = new TestHttpClient(array(), $client);
        $httpClient->get($path, $parameters);
    }

    /**
     * @expectedException \Imgur\Exception\ErrorException
     * @expectedExceptionMessage Request to: that failed with: "oops"
     */
    public function testThrowExceptionWhenBadRequestJsonError()
    {
        $path = '/some/path';
        $parameters = array('a' => 'b');

        $response = new Response(403, null, json_encode(array('data' => array('request' => 'that', 'error' => 'oops'), 'error' => 'oops2')));
        $response->addHeader('X-RateLimit-UserLimit', 10);
        $response->addHeader('X-RateLimit-UserRemaining', 10);
        $response->addHeader('X-RateLimit-ClientLimit', 10);
        $response->addHeader('X-RateLimit-ClientRemaining', 10);

        $mockPlugin = new MockPlugin();
        $mockPlugin->addResponse($response);

        $client = new GuzzleClient('http://123.com/');
        $client->addSubscriber($mockPlugin);

        $httpClient = new TestHttpClient(array(), $client);
        $httpClient->get($path, $parameters);
    }

    /**
     * @expectedException \Imgur\Exception\RuntimeException
     * @expectedExceptionMessage oops
     */
    public function testThrowExceptionWhenBadRequestNoClientMock()
    {
        $path = '/some/path';
        $parameters = array('a' => 'b');

        $response = new Response(403, null, 'oops');
        $response->addHeader('X-RateLimit-UserLimit', 10);
        $response->addHeader('X-RateLimit-UserRemaining', 10);
        $response->addHeader('X-RateLimit-ClientLimit', 10);
        $response->addHeader('X-RateLimit-ClientRemaining', 10);

        $mockPlugin = new MockPlugin();
        $mockPlugin->addResponse($response);

        $client = new GuzzleClient('http://123.com/');
        $client->addSubscriber($mockPlugin);

        $httpClient = new HttpClient(array(), $client);
        $httpClient->get($path, $parameters);
    }

    /**
     * @expectedException \Imgur\Exception\ErrorException
     */
    public function testThrowLogicException()
    {
        $path = '/some/path';
        $parameters = array('a = b');

        $response = new Response(200, null, 'data');

        $client = $this->getClientMock();
        $client->expects($this->any())
            ->method('send')
            ->will($this->throwException(new \LogicException()));

        $httpClient = new HttpClient(array(), $client);
        $response = $httpClient->get($path, $parameters);

        $this->assertInstanceOf('Guzzle\Http\Message\Response', $response);
    }

    protected function getClientMock(array $methods = array())
    {
        $mock = $this->getMock(
            'Guzzle\Http\Client',
            array_merge(
                array('send', 'createRequest'),
                $methods
            )
        );

        $mock->expects($this->any())
            ->method('createRequest')
            ->will($this->returnValue($this->getMock('Guzzle\Http\Message\Request', array(), array('GET', 'some'))));

        return $mock;
    }
}

class TestHttpClient extends HttpClient
{
    public function getOption($name, $default = null)
    {
        return isset($this->options[$name]) ? $this->options[$name] : $default;
    }

    public function performRequest($url, $parameters, $httpMethod = 'GET')
    {
        $request = $this->client->createRequest($httpMethod, $url);

        return $this->client->send($request);
    }
}
