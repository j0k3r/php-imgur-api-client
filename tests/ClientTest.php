<?php

namespace Imgur\tests;

use Imgur\Client;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    private function getHttpClientMock(array $methods = array())
    {
        $methods = array_merge(
            array('get', 'post', 'delete', 'request', 'performRequest', 'createRequest', 'parseResponse'),
            $methods
        );

        return $this->createMock('Imgur\HttpClient\HttpClientInterface', $methods);
    }

    private function getAuthenticationClientMock(array $methods = array())
    {
        $methods = array_merge(
            array('getAuthenticationUrl', 'getAccessToken', 'requestAccessToken', 'setAccessToken', 'sign', 'refreshToken'),
            $methods
        );

        return $this->createMock('Imgur\Auth\AuthInterface', $methods);
    }

    public function testNoParameters()
    {
        $client = new Client();
        $this->assertInstanceOf('Imgur\HttpClient\HttpClient', $client->getHttpClient());
        $this->assertInstanceOf('Imgur\Auth\OAuth2', $client->getAuthenticationClient());
    }

    public function testAuthenticationParameter()
    {
        $client = new Client($this->getAuthenticationClientMock());
        $this->assertInstanceOf('Imgur\HttpClient\HttpClient', $client->getHttpClient());
        $this->assertInstanceOf('Imgur\Auth\AuthInterface', $client->getAuthenticationClient());
    }

    public function testHttpParameter()
    {
        $client = new Client(null, $this->getHttpClientMock());
        $this->assertInstanceOf('Imgur\HttpClient\HttpClientInterface', $client->getHttpClient());
        $this->assertInstanceOf('Imgur\Auth\OAuth2', $client->getAuthenticationClient());
    }

    public function testBothParameter()
    {
        $client = new Client($this->getAuthenticationClientMock(), $this->getHttpClientMock());
        $this->assertInstanceOf('Imgur\HttpClient\HttpClientInterface', $client->getHttpClient());
        $this->assertInstanceOf('Imgur\Auth\AuthInterface', $client->getAuthenticationClient());
    }

    /**
     * @dataProvider getApiClassesProvider
     */
    public function testGetApiInstance($apiName, $class)
    {
        $client = new Client();
        $this->assertInstanceOf($class, $client->api($apiName));
    }

    public function getApiClassesProvider()
    {
        return array(
            array('account', 'Imgur\Api\Account'),
            array('album', 'Imgur\Api\Album'),
            array('comment', 'Imgur\Api\Comment'),
            array('gallery', 'Imgur\Api\Gallery'),
            array('image', 'Imgur\Api\Image'),
            array('conversation', 'Imgur\Api\Conversation'),
            array('notification', 'Imgur\Api\Notification'),
            array('memegen', 'Imgur\Api\Memegen'),
        );
    }

    /**
     * @expectedException \Imgur\Exception\InvalidArgumentException
     */
    public function testNotGetApiInstance()
    {
        $client = new Client();
        $client->api('do_not_exist');
    }

    /**
     * @expectedException \Imgur\Exception\InvalidArgumentException
     */
    public function testGetOptionNotDefined()
    {
        $client = new Client();
        $client->getOption('do_not_exist');
    }

    /**
     * @expectedException \Imgur\Exception\InvalidArgumentException
     */
    public function testSetOptionNotDefined()
    {
        $client = new Client();
        $client->setOption('do_not_exist', 'value');
    }

    /**
     * @dataProvider getOptions
     */
    public function testGetOption($option, $value)
    {
        $client = new Client();
        $client->setOption($option, $value);

        $this->assertEquals($value, $client->getOption($option));
    }

    public function getOptions()
    {
        return array(
            array('base_url', 'url'),
            array('client_id', 'id'),
            array('client_secret', 'secret'),
        );
    }

    public function testGetAuthenticationUrl()
    {
        $client = new Client();
        $this->assertEquals('https://api.imgur.com/oauth2/authorize?response_type=code', $client->getAuthenticationUrl());
        $this->assertEquals('https://api.imgur.com/oauth2/authorize?response_type=pin', $client->getAuthenticationUrl('pin'));
        $this->assertEquals('https://api.imgur.com/oauth2/authorize?response_type=code&state=draft', $client->getAuthenticationUrl('code', 'draft'));

        $client = new Client();
        $client->setOption('client_id', 123);
        $this->assertEquals('https://api.imgur.com/oauth2/authorize?client_id=123&response_type=pin', $client->getAuthenticationUrl('pin'));
        $this->assertEquals('https://api.imgur.com/oauth2/authorize?client_id=123&response_type=code', $client->getAuthenticationUrl());
        $this->assertEquals('https://api.imgur.com/oauth2/authorize?client_id=123&response_type=code&state=draft', $client->getAuthenticationUrl('code', 'draft'));
    }

    public function testCheckAccessTokenExpired()
    {
        $authenticationClient = $this->getAuthenticationClientMock(array('checkAccessTokenExpired'));
        $authenticationClient->expects($this->once())
            ->method('checkAccessTokenExpired')
            ->with();

        $client = new Client($authenticationClient);
        $client->checkAccessTokenExpired();
    }

    public function testRequestAccessToken()
    {
        $httpClient = $this->getHttpClientMock();
        $authenticationClient = $this->getAuthenticationClientMock();
        $authenticationClient->expects($this->once())
            ->method('requestAccessToken')
            ->with('code', 'code');

        $client = new Client($authenticationClient, $httpClient);
        $client->requestAccessToken('code');
    }

    public function testRefreshToken()
    {
        $httpClient = $this->getHttpClientMock();
        $authenticationClient = $this->getAuthenticationClientMock();
        $authenticationClient->expects($this->once())
            ->method('refreshToken');

        $client = new Client($authenticationClient, $httpClient);
        $client->refreshToken();
    }

    public function testSetAccessToken()
    {
        $httpClient = $this->getHttpClientMock();
        $authenticationClient = $this->getAuthenticationClientMock();
        $authenticationClient->expects($this->once())
            ->method('setAccessToken')
            ->with('token');

        $client = new Client($authenticationClient, $httpClient);
        $client->setAccessToken('token');
    }
}
