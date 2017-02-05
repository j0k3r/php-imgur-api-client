<?php

namespace Imgur\tests\Api;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Subscriber\Mock;
use Imgur\Api\CustomGallery;
use Imgur\Client;
use Imgur\HttpClient\HttpClient;

class CustomGalleryTest extends ApiTestCase
{
    protected function getApiClass()
    {
        return 'Imgur\Api\CustomGallery';
    }

    /**
     * @expectedException \Imgur\Exception\ErrorException
     * @expectedExceptionMessage Authentication required
     */
    public function testBaseReal()
    {
        $guzzleClient = new GuzzleClient(['base_url' => 'https://api.imgur.com/3/']);
        $httpClient = new HttpClient([], $guzzleClient);
        $client = new Client(null, $httpClient);
        $customGallery = new CustomGallery($client);

        $customGallery->customGallery();
    }

    public function testBaseWithResponse()
    {
        $guzzleClient = new GuzzleClient();
        $mock = new Mock([
            new Response(200, ['Content-Type' => 'application/json'], Stream::factory(json_encode([
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
            ]))),
        ]);
        $guzzleClient->getEmitter()->attach($mock);

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
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->customGallery());
    }

    /**
     * @expectedException \Imgur\Exception\InvalidArgumentException
     * @expectedExceptionMessage is wrong. Possible values are
     */
    public function testCustomGalleryWrongSortValue()
    {
        $this->getApiMock()->customGallery('bad sort');
    }

    /**
     * @expectedException \Imgur\Exception\InvalidArgumentException
     * @expectedExceptionMessage is wrong. Possible values are
     */
    public function testCustomGalleryWrongWindowValue()
    {
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
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->filtered());
    }

    /**
     * @expectedException \Imgur\Exception\InvalidArgumentException
     * @expectedExceptionMessage is wrong. Possible values are
     */
    public function testFilteredWrongSortValue()
    {
        $this->getApiMock()->filtered('bad sort');
    }

    /**
     * @expectedException \Imgur\Exception\InvalidArgumentException
     * @expectedExceptionMessage is wrong. Possible values are
     */
    public function testFilteredWrongWindowValue()
    {
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
            ->will($this->returnValue($expectedValue));

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
            ->will($this->returnValue($expectedValue));

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
            ->will($this->returnValue($expectedValue));

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
            ->will($this->returnValue($expectedValue));

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
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->unBlockTag('funny'));
    }
}
