<?php

namespace Imgur\tests\Api;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Imgur\Api\Image;
use Imgur\Client;
use Imgur\HttpClient\HttpClient;

class ImageTest extends ApiTestCase
{
    public function testBaseReal()
    {
        $this->expectException(\Imgur\Exception\ErrorException::class);
        $this->expectExceptionMessage('Authentication required');

        $guzzleClient = new GuzzleClient(['base_uri' => 'https://api.imgur.com/3/']);
        $httpClient = new HttpClient([], $guzzleClient);
        $client = new Client(null, $httpClient);
        $image = new Image($client);

        $image->image('ZOY11VC');
    }

    public function testBaseWithResponse()
    {
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'data' => [
                    'id' => 'ZOY11VC',
                    'title' => null,
                    'description' => null,
                    'datetime' => 1462804175,
                    'type' => 'image/png',
                    'animated' => false,
                    'width' => 1590,
                    'height' => 1188,
                    'size' => 2773549,
                    'views' => 14,
                    'bandwidth' => 38829686,
                    'vote' => null,
                    'favorite' => false,
                    'nsfw' => null,
                    'section' => null,
                    'account_url' => null,
                    'account_id' => null,
                    'is_ad' => false,
                    'in_gallery' => false,
                    'deletehash' => 'LxuxLY2vnVKtfug',
                    'name' => null,
                    'link' => 'http://i.imgur.com/ZOY11VC.png',
                ],
                'success' => true,
                'status' => 200,
            ])),
        ]);
        $handler = HandlerStack::create($mock);
        $guzzleClient = new GuzzleClient(['handler' => $handler]);

        $httpClient = new HttpClient([], $guzzleClient);
        $client = new Client(null, $httpClient);
        $image = new Image($client);

        $result = $image->image('ZOY11VC');

        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('title', $result);
        $this->assertArrayHasKey('description', $result);
        $this->assertArrayHasKey('datetime', $result);
        $this->assertArrayHasKey('type', $result);
        $this->assertArrayHasKey('animated', $result);
        $this->assertArrayHasKey('width', $result);
        $this->assertArrayHasKey('height', $result);
        $this->assertArrayHasKey('size', $result);
        $this->assertArrayHasKey('views', $result);
        $this->assertArrayHasKey('bandwidth', $result);
        $this->assertArrayHasKey('vote', $result);
        $this->assertArrayHasKey('favorite', $result);
        $this->assertArrayHasKey('nsfw', $result);
        $this->assertArrayHasKey('section', $result);
        $this->assertArrayHasKey('account_url', $result);
        $this->assertArrayHasKey('account_id', $result);
        $this->assertArrayHasKey('is_ad', $result);
        $this->assertArrayHasKey('in_gallery', $result);
        $this->assertArrayHasKey('deletehash', $result);
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('link', $result);
    }

    public function testImage()
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
            ->with('image/ZOY11VC')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->image('ZOY11VC'));
    }

    public function testUploadWithUrl()
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('image')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->upload(['type' => 'url', 'image' => 'http://i.imgur.com/ZOY11VC.png']));
    }

    public function testUploadWithFile()
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('image')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->upload(['type' => 'file', 'image' => __DIR__ . '/ZOY11VC.png']));
    }

    public function testUploadWithBadType()
    {
        $this->expectException(\Imgur\Exception\InvalidArgumentException::class);
        $this->expectExceptionMessage('is wrong. Possible values are');

        $this->getApiMock()->upload(['type' => 'other', 'image' => 'http://i.imgur.com/ZOY11VC.png']);
    }

    public function testUploadWithUrlParamMissing()
    {
        $this->expectException(\Imgur\Exception\MissingArgumentException::class);
        $this->expectExceptionMessage('parameters is missing');

        $this->getApiMock()->upload([]);
    }

    public function testDeleteImage()
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with('image/ZOY11VC')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->deleteImage('ZOY11VC'));
    }

    public function testUpdate()
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('image/ZOY11VC')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->update('ZOY11VC', ['title' => 'hihihi']));
    }

    public function testFavorite()
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('image/ZOY11VC/favorite')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->favorite('ZOY11VC'));
    }

    protected function getApiClass()
    {
        return 'Imgur\Api\Image';
    }
}
