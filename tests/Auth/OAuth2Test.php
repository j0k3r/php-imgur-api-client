<?php

namespace Imgur\Tests\Auth;

use Imgur\HttpClient\HttpClient;
use Imgur\Auth\OAuth2;
use Guzzle\Http\Message\Response;
use Guzzle\Plugin\Mock\MockPlugin;
use Guzzle\Http\Client as GuzzleClient;

class OAuth2Test extends \PHPUnit_Framework_TestCase
{
    public function testGetAuthenticationUrl()
    {
        $auth = new OAuth2($this->getClientMock(), 123, 456);
        $this->assertEquals('https://api.imgur.com/oauth2/authorize?client_id=123&response_type=code', $auth->getAuthenticationUrl());
        $this->assertEquals('https://api.imgur.com/oauth2/authorize?client_id=123&response_type=pin', $auth->getAuthenticationUrl('pin'));
        $this->assertEquals('https://api.imgur.com/oauth2/authorize?client_id=123&response_type=code&state=draft', $auth->getAuthenticationUrl('code', 'draft'));

        $auth = new OAuth2($this->getClientMock(), 456, 789);
        $this->assertEquals('https://api.imgur.com/oauth2/authorize?client_id=456&response_type=pin', $auth->getAuthenticationUrl('pin'));
        $this->assertEquals('https://api.imgur.com/oauth2/authorize?client_id=456&response_type=code', $auth->getAuthenticationUrl());
        $this->assertEquals('https://api.imgur.com/oauth2/authorize?client_id=456&response_type=code&state=draft', $auth->getAuthenticationUrl('code', 'draft'));
    }

    /**
     * @expectedException \Imgur\Exception\AuthException
     * @expectedExceptionMessage Request for access token failed
     */
    public function testRequestAccessTokenBadStatusCode()
    {
        $auth = new OAuth2($this->getClientMock(400), 123, 456);
        $auth->requestAccessToken('code', null);
    }

    public function testRequestAccessTokenWithCode()
    {
        $auth = new OAuth2($this->getClientMock(200, json_encode(array('access_token' => 'T0K3N'))), 123, 456);
        $result = $auth->requestAccessToken('code', null);

        $this->assertArrayHasKey('access_token', $result);
        $this->assertArrayHasKey('created_at', $result);
        $this->assertEquals('T0K3N', $result['access_token']);
        $this->assertGreaterThanOrEqual(time(), $result['created_at']);
    }

    public function testRequestAccessTokenWithPin()
    {
        $auth = new OAuth2($this->getClientMock(200, json_encode(array('data' => array('access_token' => 'T0K3N')))), 123, 456);
        $result = $auth->requestAccessToken('code', 'pin');

        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('access_token', $result['data']);
        $this->assertArrayHasKey('created_at', $result);
        $this->assertEquals('T0K3N', $result['data']['access_token']);
        $this->assertLessThanOrEqual(time(), $result['created_at']);
    }

    /**
     * @expectedException \Imgur\Exception\AuthException
     * @expectedExceptionMessage Request for refresh access token failed. oops
     */
    public function testRefreshTokenBadStatusCode()
    {
        $auth = new OAuth2($this->getClientMock(400, json_encode(array('error' => 'oops'))), 123, 456);
        $auth->refreshToken();
    }

    public function testRefreshToken()
    {
        $auth = new OAuth2($this->getClientMock(200, json_encode(array('access_token' => 'T0K3N', 'refresh_token' => 'FR35H'))), 123, 456);
        $result = $auth->refreshToken();

        $this->assertArrayHasKey('access_token', $result);
        $this->assertEquals('T0K3N', $result['access_token']);
    }

    /**
     * @expectedException \Imgur\Exception\AuthException
     * @expectedExceptionMessage Token is not a valid json string.
     */
    public function testSetAccessTokenNull()
    {
        $auth = new OAuth2($this->getClientMock(), 123, 456);
        $result = $auth->setAccessToken(null);
    }

    /**
     * @expectedException \Imgur\Exception\AuthException
     * @expectedExceptionMessage Access token could not be retrieved from the decoded json response.
     */
    public function testSetAccessTokenEmpty()
    {
        $auth = new OAuth2($this->getClientMock(), 123, 456);
        $auth->setAccessToken(array('data'));
    }

    public function testCheckAccessTokenExpiredFromScratch()
    {
        $auth = new OAuth2($this->getClientMock(), 123, 456);
        $this->assertTrue($auth->checkAccessTokenExpired());
    }

    public function testCheckAccessTokenExpired()
    {
        $auth = new OAuth2($this->getClientMock(200, json_encode(array(
            'access_token' => 'T0K3N',
            'expires_in' => 3600,
        ))), 123, 456);
        $auth->requestAccessToken('code', null);

        $this->assertFalse($auth->checkAccessTokenExpired());
    }

    public function testAuthenticatedRequest()
    {
        $response = new Response(200, null, json_encode(array(
            'access_token' => 'T0K3N',
            'expires_in' => 3600,
        )));

        $mockPlugin = new MockPlugin();
        $mockPlugin->addResponse($response);
        $mockPlugin->addResponse(new Response(200));

        $client = new GuzzleClient('http://123.com/');
        $client->addSubscriber($mockPlugin);

        $httpClient = new HttpClient(array(), $client);

        $auth = new OAuth2($httpClient, 123, 456);
        $auth->requestAccessToken('code', null);

        $httpClient->get('http://google.com');
    }

    protected function getClientMock($statusCode = 200, $body = null)
    {
        $client = $this->getMock('Guzzle\Http\Client', array('addListener', 'post', 'get', 'send'));

        $response = $this->getMockBuilder('Guzzle\Http\Message\Response')
            ->disableOriginalConstructor()
            ->getMock();
        $response->expects($this->any())
            ->method('getBody')
            ->willReturn($body);
        $response->expects($this->any())
            ->method('getStatusCode')
            ->willReturn($statusCode);

        $client->expects($this->any())
            ->method('send')
            ->will($this->returnValue($response));

        return new HttpClient(array(), $client);
    }
}
