<?php

namespace Imgur\tests\Api;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Imgur\Api\Memegen;
use Imgur\Client;
use Imgur\HttpClient\HttpClient;

class MemegenTest extends ApiTestCase
{
    public function testBaseReal()
    {
        $this->expectException(\Imgur\Exception\ErrorException::class);
        $this->expectExceptionMessage('Authentication required');

        $guzzleClient = new GuzzleClient(['base_uri' => 'https://api.imgur.com/3/']);
        $httpClient = new HttpClient([], $guzzleClient);
        $client = new Client(null, $httpClient);
        $memegen = new Memegen($client);

        $memegen->defaultMemes();
    }

    public function testBaseWithResponse()
    {
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'data' => [
                    [
                        'id' => '2tNR7P7',
                        'title' => "You're Gonna Have A Bad Time",
                        'description' => null,
                        'datetime' => 1372266973,
                        'type' => 'image/png',
                        'animated' => false,
                        'width' => 597,
                        'height' => 402,
                        'size' => 248849,
                        'views' => 447547,
                        'bandwidth' => 111371623403,
                        'vote' => null,
                        'favorite' => false,
                        'nsfw' => false,
                        'section' => null,
                        'account_url' => null,
                        'account_id' => 4,
                        'is_ad' => false,
                        'in_gallery' => false,
                        'link' => 'http://i.imgur.com/2tNR7P7.png',
                    ],
                ],
                'success' => true,
                'status' => 200,
            ])),
        ]);
        $handler = HandlerStack::create($mock);
        $guzzleClient = new GuzzleClient(['handler' => $handler]);

        $httpClient = new HttpClient([], $guzzleClient);
        $client = new Client(null, $httpClient);
        $memegen = new Memegen($client);

        $result = $memegen->defaultMemes();

        $this->assertArrayHasKey('id', $result[0]);
        $this->assertArrayHasKey('title', $result[0]);
        $this->assertArrayHasKey('description', $result[0]);
        $this->assertArrayHasKey('datetime', $result[0]);
        $this->assertArrayHasKey('type', $result[0]);
        $this->assertArrayHasKey('animated', $result[0]);
        $this->assertArrayHasKey('width', $result[0]);
        $this->assertArrayHasKey('height', $result[0]);
        $this->assertArrayHasKey('size', $result[0]);
        $this->assertArrayHasKey('views', $result[0]);
        $this->assertArrayHasKey('bandwidth', $result[0]);
        $this->assertArrayHasKey('vote', $result[0]);
        $this->assertArrayHasKey('favorite', $result[0]);
        $this->assertArrayHasKey('nsfw', $result[0]);
        $this->assertArrayHasKey('section', $result[0]);
        $this->assertArrayHasKey('account_url', $result[0]);
        $this->assertArrayHasKey('account_id', $result[0]);
        $this->assertArrayHasKey('is_ad', $result[0]);
        $this->assertArrayHasKey('in_gallery', $result[0]);
        $this->assertArrayHasKey('link', $result[0]);
    }

    public function testMemegen()
    {
        $expectedValue = [
            'data' => [
                'id' => 'ZOY11VC',
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('memegen/defaults')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->defaultMemes());
    }

    protected function getApiClass()
    {
        return 'Imgur\Api\Memegen';
    }
}
