<?php

namespace Imgur\Tests\Api;

use Guzzle\Http\Message\Response;
use Imgur\Api\AbstractApi;

class AbstractApiTest extends \PHPUnit_Framework_TestCase
{
    public function testGETRequestToClient()
    {
        $expectedArray = array('value');

        $httpClient = $this->getHttpMock();
        $httpClient
            ->expects($this->any())
            ->method('get')
            ->with('/path', array('param1' => 'param1value'))
            ->will($this->returnValue($expectedArray));

        $client = $this->getClientMock();
        $client->setHttpClient($httpClient);

        $api = $this->getAbstractApiObject($client);

        $this->assertEquals($expectedArray, $api->get('/path', array('param1' => 'param1value')));
    }

    public function shouldPassPOSTRequestToClient()
    {
        $expectedArray = array('value');

        $httpClient = $this->getHttpMock();
        $httpClient
            ->expects($this->once())
            ->method('post')
            ->with('/path', array('param1' => 'param1value'), array('option1' => 'option1value'))
            ->will($this->returnValue($expectedArray));
        $client = $this->getClientMock();
        $client->setHttpClient($httpClient);

        $api = $this->getAbstractApiObject($client);

        $this->assertEquals($expectedArray, $api->post('/path', array('param1' => 'param1value'), array('option1' => 'option1value')));
    }

    public function shouldPassPATCHRequestToClient()
    {
        $expectedArray = array('value');

        $httpClient = $this->getHttpMock();
        $httpClient
            ->expects($this->once())
            ->method('patch')
            ->with('/path', array('param1' => 'param1value'), array('option1' => 'option1value'))
            ->will($this->returnValue($expectedArray));
        $client = $this->getClientMock();
        $client->setHttpClient($httpClient);

        $api = $this->getAbstractApiObject($client);

        $this->assertEquals($expectedArray, $api->patch('/path', array('param1' => 'param1value'), array('option1' => 'option1value')));
    }

    public function shouldPassPUTRequestToClient()
    {
        $expectedArray = array('value');

        $httpClient = $this->getHttpMock();
        $httpClient
            ->expects($this->once())
            ->method('put')
            ->with('/path', array('param1' => 'param1value'), array('option1' => 'option1value'))
            ->will($this->returnValue($expectedArray));
        $client = $this->getClientMock();
        $client->setHttpClient($httpClient);

        $api = $this->getAbstractApiObject($client);

        $this->assertEquals($expectedArray, $api->put('/path', array('param1' => 'param1value'), array('option1' => 'option1value')));
    }

    public function shouldPassDELETERequestToClient()
    {
        $expectedArray = array('value');

        $httpClient = $this->getHttpMock();
        $httpClient
            ->expects($this->once())
            ->method('delete')
            ->with('/path', array('param1' => 'param1value'), array('option1' => 'option1value'))
            ->will($this->returnValue($expectedArray));
        $client = $this->getClientMock();
        $client->setHttpClient($httpClient);

        $api = $this->getAbstractApiObject($client);

        $this->assertEquals($expectedArray, $api->delete('/path', array('param1' => 'param1value'), array('option1' => 'option1value')));
    }

    public function shouldNotPassEmptyRefToClient()
    {
        $expectedResponse = new Response('value');

        $httpClient = $this->getHttpMock();
        $httpClient
            ->expects($this->any())
            ->method('get')
            ->with('/path', array())
            ->will($this->returnValue($expectedResponse));
        $client = $this->getClientMock();
        $client->setHttpClient($httpClient);

        $api = new ExposedAbstractApiTestInstance($client, null);
        $api->get('/path', array('ref' => null));
    }

    protected function getAbstractApiObject($client)
    {
        return new AbstractApiTestInstance($client, null);
    }

    protected function getClientMock()
    {
        return new \Imgur\Client(null, $this->getHttpMock());
    }

    protected function getHttpMock()
    {
        return $this->createMock('Imgur\HttpClient\HttpClient', array(), array(array(), $this->getHttpClientMock()));
    }

    protected function getHttpClientMock()
    {
        $mock = $this->createMock('Guzzle\Http\Client', array('send'));
        $mock
            ->expects($this->any())
            ->method('send');

        return $mock;
    }
}

class AbstractApiTestInstance extends AbstractApi
{
    /**
     * {@inheritdoc}
     */
    public function get($path, $parameters = array())
    {
        return $this->client->getHttpClient()->get($path, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function post($path, $parameters = array())
    {
        return $this->client->getHttpClient()->post($path, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function delete($path, $parameters = array())
    {
        return $this->client->getHttpClient()->delete($path, $parameters);
    }
}

class ExposedAbstractApiTestInstance extends AbstractApi
{
    /**
     * {@inheritdoc}
     */
    public function get($path, $parameters = array())
    {
        return parent::get($path, $parameters);
    }
}
