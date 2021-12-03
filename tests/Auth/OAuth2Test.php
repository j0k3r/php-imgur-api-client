<?php

namespace Imgur\tests\Auth;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Imgur\Auth\OAuth2;
use Imgur\Exception\AuthException;
use Imgur\HttpClient\HttpClient;
use PHPUnit\Framework\TestCase;

class OAuth2Test extends TestCase
{
    public function testGetAuthenticationUrl(): void
    {
        $auth = new OAuth2(new HttpClient(), '123', '456');
        $this->assertSame('https://api.imgur.com/oauth2/authorize?client_id=123&response_type=code', $auth->getAuthenticationUrl());
        $this->assertSame('https://api.imgur.com/oauth2/authorize?client_id=123&response_type=pin', $auth->getAuthenticationUrl('pin'));
        $this->assertSame('https://api.imgur.com/oauth2/authorize?client_id=123&response_type=code&state=draft', $auth->getAuthenticationUrl('code', 'draft'));

        $auth = new OAuth2(new HttpClient(), '456', '789');
        $this->assertSame('https://api.imgur.com/oauth2/authorize?client_id=456&response_type=pin', $auth->getAuthenticationUrl('pin'));
        $this->assertSame('https://api.imgur.com/oauth2/authorize?client_id=456&response_type=code', $auth->getAuthenticationUrl());
        $this->assertSame('https://api.imgur.com/oauth2/authorize?client_id=456&response_type=code&state=draft', $auth->getAuthenticationUrl('code', 'draft'));
    }

    public function testRequestAccessTokenBadStatusCode(): void
    {
        $this->expectException(AuthException::class);
        $this->expectExceptionMessage('Request for access token failed');

        $mock = new MockHandler([
            new Response(400),
        ]);
        $handler = HandlerStack::create($mock);
        $client = new GuzzleClient(['handler' => $handler]);

        $auth = new OAuth2(new HttpClient([], $client, $handler), '123', '456');
        $auth->requestAccessToken('code', null);
    }

    public function testRequestAccessTokenWithCode(): void
    {
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], (string) json_encode(['access_token' => 'T0K3N'])),
        ]);
        $handler = HandlerStack::create($mock);
        $client = new GuzzleClient(['handler' => $handler]);

        $auth = new OAuth2(new HttpClient([], $client, $handler), '123', '456');
        $result = $auth->requestAccessToken('code', null);

        $this->assertArrayHasKey('access_token', $result);
        $this->assertArrayHasKey('created_at', $result);
        $this->assertSame('T0K3N', $result['access_token']);
        $this->assertGreaterThanOrEqual(time(), $result['created_at']);
    }

    public function testRequestAccessTokenWithPin(): void
    {
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], (string) json_encode(['access_token' => 'T0K3N'])),
        ]);
        $handler = HandlerStack::create($mock);
        $client = new GuzzleClient(['handler' => $handler]);

        $auth = new OAuth2(new HttpClient([], $client, $handler), '123', '456');
        $result = $auth->requestAccessToken('code', 'pin');

        $this->assertArrayHasKey('access_token', $result);
        $this->assertArrayHasKey('created_at', $result);
        $this->assertSame('T0K3N', $result['access_token']);
        $this->assertLessThanOrEqual(time(), $result['created_at']);
    }

    public function testRefreshTokenBadStatusCode(): void
    {
        $this->expectException(AuthException::class);
        $this->expectExceptionMessage('Request for refresh access token failed');

        $mock = new MockHandler([
            new Response(400, ['Content-Type' => 'application/json'], (string) json_encode(['data' => ['request' => '/3/account', 'error' => 'oops2', 'method' => 'GET'], 'success' => false, 'status' => 400])),
        ]);
        $handler = HandlerStack::create($mock);
        $client = new GuzzleClient(['handler' => $handler]);

        $auth = new OAuth2(new HttpClient([], $client, $handler), '123', '456');
        $auth->refreshToken();
    }

    public function testRefreshToken(): void
    {
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], (string) json_encode(['access_token' => 'T0K3N', 'refresh_token' => 'FR35H'])),
        ]);
        $handler = HandlerStack::create($mock);
        $client = new GuzzleClient(['handler' => $handler]);

        $auth = new OAuth2(new HttpClient([], $client, $handler), '123', '456');
        $result = $auth->refreshToken();

        $this->assertArrayHasKey('access_token', $result);
        $this->assertSame('T0K3N', $result['access_token']);
    }

    public function testSetAccessTokenNull(): void
    {
        $this->expectException(AuthException::class);
        $this->expectExceptionMessage('Token is not a valid json string.');

        $client = new GuzzleClient();
        $auth = new OAuth2(new HttpClient(), '123', '456');
        $auth->setAccessToken(null);
    }

    public function testSetAccessTokenEmpty(): void
    {
        $this->expectException(AuthException::class);
        $this->expectExceptionMessage('Access token could not be retrieved from the decoded json response.');

        $client = new GuzzleClient();
        $auth = new OAuth2(new HttpClient(), '123', '456');
        $auth->setAccessToken(['data']);
    }

    public function testCheckAccessTokenExpiredFromScratch(): void
    {
        $client = new GuzzleClient();
        $auth = new OAuth2(new HttpClient(), '123', '456');
        $this->assertTrue($auth->checkAccessTokenExpired());
    }

    public function testCheckAccessTokenExpired(): void
    {
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], (string) json_encode(['access_token' => 'T0K3N', 'expires_in' => 3600])),
        ]);
        $handler = HandlerStack::create($mock);
        $client = new GuzzleClient(['handler' => $handler]);

        $auth = new OAuth2(new HttpClient([], $client, $handler), '123', '456');
        $auth->requestAccessToken('code', null);

        $this->assertFalse($auth->checkAccessTokenExpired());
    }

    public function testAuthenticatedRequest(): void
    {
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], (string) json_encode(['data' => ['access_token' => 'T0K3N', 'expires_in' => 3600]])),
            new Response(200),
        ]);
        $handler = HandlerStack::create($mock);
        $client = new GuzzleClient(['handler' => $handler]);

        $httpClient = new HttpClient([], $client, $handler, $handler);

        $auth = new OAuth2($httpClient, '123', '456');
        $auth->requestAccessToken('code', null);

        $httpClient->get('http://google.com');

        $this->assertTrue($auth->checkAccessTokenExpired());
    }
}
