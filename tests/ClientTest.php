<?php

namespace Imgur\tests;

use Imgur\Auth\OAuth2;
use Imgur\Client;
use Imgur\Exception\InvalidArgumentException;
use Imgur\HttpClient\HttpClient;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    public function testNoParameters(): void
    {
        $client = new Client();
        $client->setOption('client_id', 'xx');
        $client->setOption('client_secret', 'xx');

        $this->assertInstanceOf(HttpClient::class, $client->getHttpClient());
        $this->assertInstanceOf(OAuth2::class, $client->getAuthenticationClient());
    }

    public function testAuthenticationParameter(): void
    {
        $authenticationClient = $this->getAuthenticationClientMock();
        $client = new Client($authenticationClient);
        $client->setOption('client_id', 'xx');
        $client->setOption('client_secret', 'xx');

        $this->assertInstanceOf(HttpClient::class, $client->getHttpClient());
        $this->assertInstanceOf(OAuth2::class, $client->getAuthenticationClient());
    }

    public function testHttpParameter(): void
    {
        $httpClient = $this->getHttpClientMock();
        $client = new Client(null, $httpClient);
        $client->setOption('client_id', 'xx');
        $client->setOption('client_secret', 'xx');

        $this->assertInstanceOf(HttpClient::class, $client->getHttpClient());
        $this->assertInstanceOf(OAuth2::class, $client->getAuthenticationClient());
    }

    public function testBothParameter(): void
    {
        $httpClient = $this->getHttpClientMock();
        $authenticationClient = $this->getAuthenticationClientMock();
        $client = new Client($authenticationClient, $httpClient);
        $client->setOption('client_id', 'xx');
        $client->setOption('client_secret', 'xx');

        $this->assertInstanceOf(HttpClient::class, $client->getHttpClient());
        $this->assertInstanceOf(OAuth2::class, $client->getAuthenticationClient());
    }

    /**
     * @dataProvider getApiClassesProvider
     *
     * @param class-string $class
     */
    public function testGetApiInstance(string $apiName, $class): void
    {
        $client = new Client();
        $client->setOption('client_id', 'xx');
        $client->setOption('client_secret', 'xx');

        $this->assertInstanceOf($class, $client->api($apiName));
    }

    public function getApiClassesProvider(): array
    {
        return [
            ['account', \Imgur\Api\Account::class],
            ['album', \Imgur\Api\Album::class],
            ['comment', \Imgur\Api\Comment::class],
            ['gallery', \Imgur\Api\Gallery::class],
            ['image', \Imgur\Api\Image::class],
            ['conversation', \Imgur\Api\Conversation::class],
            ['notification', \Imgur\Api\Notification::class],
            ['memegen', \Imgur\Api\Memegen::class],
            ['customGallery', \Imgur\Api\CustomGallery::class],
            ['topic', \Imgur\Api\Topic::class],
        ];
    }

    public function testNotGetApiInstance(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $client = new Client();
        $client->setOption('client_id', 'xx');
        $client->setOption('client_secret', 'xx');
        $client->api('do_not_exist');
    }

    public function testGetOptionNotDefined(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $client = new Client();
        $client->getOption('do_not_exist');
    }

    public function testSetOptionNotDefined(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $client = new Client();
        $client->setOption('do_not_exist', 'value');
    }

    /**
     * @dataProvider getOptions
     */
    public function testGetOption(string $option, string $value): void
    {
        $client = new Client();
        $client->setOption($option, $value);

        $this->assertSame($value, $client->getOption($option));
    }

    public function getOptions(): array
    {
        return [
            ['base_url', 'url'],
            ['client_id', 'id'],
            ['client_secret', 'secret'],
        ];
    }

    public function testGetAuthenticationUrl(): void
    {
        // $client = new Client();
        // $this->assertSame('https://api.imgur.com/oauth2/authorize?response_type=code', $client->getAuthenticationUrl());
        // $this->assertSame('https://api.imgur.com/oauth2/authorize?response_type=pin', $client->getAuthenticationUrl('pin'));
        // $this->assertSame('https://api.imgur.com/oauth2/authorize?response_type=code&state=draft', $client->getAuthenticationUrl('code', 'draft'));

        $client = new Client();
        $client->setOption('client_id', '123');
        $client->setOption('client_secret', 'xx');
        $this->assertSame('https://api.imgur.com/oauth2/authorize?client_id=123&response_type=pin', $client->getAuthenticationUrl('pin'));
        $this->assertSame('https://api.imgur.com/oauth2/authorize?client_id=123&response_type=code', $client->getAuthenticationUrl());
        $this->assertSame('https://api.imgur.com/oauth2/authorize?client_id=123&response_type=code&state=draft', $client->getAuthenticationUrl('code', 'draft'));
    }

    public function testCheckAccessTokenExpired(): void
    {
        $authenticationClient = $this->getAuthenticationClientMock();
        $authenticationClient->expects($this->once())
            ->method('checkAccessTokenExpired')
            ->with();

        $client = new Client($authenticationClient);
        $client->checkAccessTokenExpired();
    }

    public function testRequestAccessToken(): void
    {
        $httpClient = $this->getHttpClientMock();
        $authenticationClient = $this->getAuthenticationClientMock();
        $authenticationClient->expects($this->once())
            ->method('requestAccessToken')
            ->with('code', 'code');

        $client = new Client($authenticationClient, $httpClient);
        $client->requestAccessToken('code');
    }

    public function testRefreshToken(): void
    {
        $httpClient = $this->getHttpClientMock();
        $authenticationClient = $this->getAuthenticationClientMock();
        $authenticationClient->expects($this->once())
            ->method('refreshToken');

        $client = new Client($authenticationClient, $httpClient);
        $client->refreshToken();
    }

    public function testSetAccessToken(): void
    {
        $httpClient = $this->getHttpClientMock();
        $authenticationClient = $this->getAuthenticationClientMock();
        $authenticationClient->expects($this->once())
            ->method('setAccessToken')
            ->with(['token']);

        $client = new Client($authenticationClient, $httpClient);
        $client->setAccessToken(['token']);
    }

    /**
     * @return HttpClient&MockObject
     */
    private function getHttpClientMock(array $methods = []): object
    {
        return $this->getMockBuilder(HttpClient::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['get', 'post', 'delete', 'performRequest', 'parseResponse'])
            ->addMethods(array_merge_recursive(['request', 'createRequest'], $methods))
            ->getMock();
    }

    /**
     * @return OAuth2&MockObject
     */
    private function getAuthenticationClientMock()
    {
        return $this->getMockBuilder('Imgur\Auth\OAuth2')
            ->disableOriginalConstructor()
            ->getMock();
    }
}
