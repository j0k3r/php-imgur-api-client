<?php

namespace Imgur\tests\Api;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Imgur\Api\CustomGallery;
use Imgur\Client;
use Imgur\Exception\InvalidArgumentException;
use Imgur\HttpClient\HttpClient;

class CustomGalleryTest extends ApiTestCase
{
    public function testBaseReal()
    {
        $this->expectException(\Imgur\Exception\ErrorException::class);
        $this->expectExceptionMessage('Authentication required');

        $guzzleClient = new GuzzleClient(['base_uri' => 'https://api.imgur.com/3/']);
        $httpClient = new HttpClient([], $guzzleClient);
        $client = new Client(null, $httpClient);
        $customGallery = new CustomGallery($client);

        $customGallery->customGallery();
    }

    public function testBaseWithResponse()
    {
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'data' => [
                    'link' => 'http://imgur.com/custom',
                    'tags' => [
                        'funny',
                    ],
                    'account_url' => 'j0k3rx',
                    'item_count' => 60,
                    'items' => [
                        [
                            'id' => 'AuNfEmn',
                            'title' => 'Hello all',
                            'description' => '#aww #transporn #awesome #funny #cat',
                            'datetime' => 1474800437,
                            'type' => 'image/jpeg',
                            'animated' => false,
                            'width' => 640,
                            'height' => 640,
                            'size' => 95722,
                            'views' => 2801,
                            'bandwidth' => 268117322,
                            'vote' => null,
                            'favorite' => false,
                            'nsfw' => false,
                            'section' => '',
                            'account_url' => 'cas11',
                            'account_id' => 31994106,
                            'is_ad' => false,
                            'in_gallery' => true,
                            'topic' => 'Aww',
                            'topic_id' => 8,
                            'link' => 'http://i.imgur.com/AuNfEmn.jpg',
                            'comment_count' => 10,
                            'ups' => 82,
                            'downs' => 11,
                            'po' => 71,
                            'score' => 72,
                            'is_album' => false,
                        ],
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
        $customGallery = new CustomGallery($client);

        $result = $customGallery->customGallery();

        $this->assertArrayHasKey('link', $result);
        $this->assertArrayHasKey('tags', $result);
        $this->assertArrayHasKey('account_url', $result);
        $this->assertArrayHasKey('item_count', $result);
        $this->assertArrayHasKey('items', $result);
    }

    public function testCustomGallery()
    {
        $expectedValue = [
            'data' => [
                'link' => 'http://imgur.com/custom',
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('g/custom/viral/week/0')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->customGallery());
    }

    public function testCustomGalleryWrongSortValue()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('is wrong. Possible values are');

        $this->getApiMock()->customGallery('bad sort');
    }

    public function testCustomGalleryWrongWindowValue()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('is wrong. Possible values are');

        $this->getApiMock()->customGallery('viral', 0, 'bad window');
    }

    public function testFiltered()
    {
        $expectedValue = [
            'data' => [
                'link' => 'http://imgur.com/custom',
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('g/filtered/viral/week/0')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->filtered());
    }

    public function testFilteredWrongSortValue()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('is wrong. Possible values are');

        $this->getApiMock()->filtered('bad sort');
    }

    public function testFilteredWrongWindowValue()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('is wrong. Possible values are');

        $this->getApiMock()->filtered('viral', 0, 'bad window');
    }

    public function testImage()
    {
        $expectedValue = [
            'data' => [
                'id' => 'ccWiaRJ',
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('g/custom/ccWiaRJ')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->image('ccWiaRJ'));
    }

    public function testAddTags()
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('put')
            ->with('g/add_tags', ['tags' => 'cats,funny'])
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->addTags(['cats', 'funny']));
    }

    public function testRemoveTags()
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with('g/remove_tags', ['tags' => 'cats,funny'])
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->removeTags(['cats', 'funny']));
    }

    public function testBlockTag()
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('g/block_tag', ['tag' => 'funny'])
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->blockTag('funny'));
    }

    public function testUnBlockTag()
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('g/unblock_tag', ['tag' => 'funny'])
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->unBlockTag('funny'));
    }

    protected function getApiClass()
    {
        return 'Imgur\Api\CustomGallery';
    }
}
