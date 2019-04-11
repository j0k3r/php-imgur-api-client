<?php

namespace Imgur\tests\Api;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Imgur\Api\Album;
use Imgur\Client;
use Imgur\HttpClient\HttpClient;

class AlbumTest extends ApiTestCase
{
    /**
     * @expectedException \Imgur\Exception\ErrorException
     * @expectedExceptionMessage Authentication required
     */
    public function testBaseReal()
    {
        $guzzleClient = new GuzzleClient(['base_uri' => 'https://api.imgur.com/3/']);
        $httpClient = new HttpClient([], $guzzleClient);
        $client = new Client(null, $httpClient);
        $album = new Album($client);

        $album->album('VOMXz');
    }

    public function testBaseWithResponse()
    {
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'data' => [
                    'id' => 'VOMXz',
                    'title' => 'sample',
                    'description' => null,
                    'datetime' => 1355481326,
                    'cover' => '8fhKz',
                    'cover_width' => 133,
                    'cover_height' => 100,
                    'account_url' => 'j0k3rx',
                    'account_id' => 703058,
                    'privacy' => 'hidden',
                    'layout' => 'grid',
                    'views' => 1,
                    'link' => 'http://imgur.com/a/VOMXz',
                    'favorite' => false,
                    'nsfw' => null,
                    'section' => null,
                    'images_count' => 27,
                    'in_gallery' => false,
                    'is_ad' => false,
                    'deletehash' => 'ad8ea520ef326a1',
                    'images' => [
                        [
                            'id' => 'POvvB',
                            'title' => null,
                            'description' => null,
                            'datetime' => 1355477579,
                            'type' => 'image/jpeg',
                            'animated' => false,
                            'width' => 864,
                            'height' => 576,
                            'size' => 120253,
                            'views' => 135,
                            'bandwidth' => 16234155,
                            'vote' => null,
                            'favorite' => false,
                            'nsfw' => null,
                            'section' => null,
                            'account_url' => null,
                            'account_id' => null,
                            'is_ad' => false,
                            'in_gallery' => false,
                            'deletehash' => '9taIZGNWtgODVw2',
                            'name' => 'sample',
                            'link' => 'http://i.imgur.com/POvvB.jpg',
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
        $album = new Album($client);

        $result = $album->album('VOMXz');

        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('title', $result);
        $this->assertArrayHasKey('description', $result);
        $this->assertArrayHasKey('datetime', $result);
        $this->assertArrayHasKey('cover', $result);
        $this->assertArrayHasKey('cover_width', $result);
        $this->assertArrayHasKey('cover_height', $result);
        $this->assertArrayHasKey('account_url', $result);
        $this->assertArrayHasKey('account_id', $result);
        $this->assertArrayHasKey('privacy', $result);
        $this->assertArrayHasKey('layout', $result);
        $this->assertArrayHasKey('views', $result);
        $this->assertArrayHasKey('link', $result);
        $this->assertArrayHasKey('favorite', $result);
        $this->assertArrayHasKey('nsfw', $result);
        $this->assertArrayHasKey('section', $result);
        $this->assertArrayHasKey('images_count', $result);
        $this->assertArrayHasKey('in_gallery', $result);
        $this->assertArrayHasKey('is_ad', $result);
        $this->assertArrayHasKey('deletehash', $result);
        $this->assertArrayHasKey('images', $result);
    }

    public function testAlbum()
    {
        $expectedValue = [
            'data' => [
                'id' => 'VOMXz',
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('album/VOMXz')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->album('VOMXz'));
    }

    public function testAlbumImages()
    {
        $expectedValue = [
            'data' => [
                'id' => 'VOMXz',
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('album/VOMXz/images')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->albumImages('VOMXz'));
    }

    public function testAlbumImage()
    {
        $expectedValue = [
            'data' => [
                'id' => 'POvvB',
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('album/VOMXz/image/POvvB')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->albumImage('VOMXz', 'POvvB'));
    }

    public function testCreate()
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('album')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->create('VOMXz'));
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
            ->with('album/VOMXz')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->update('VOMXz', [
            'title' => 'New title',
        ]));
    }

    public function testDeleteAlbum()
    {
        $expectedValue = [
            'data' => [
                'id' => 'VOMXz',
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with('album/VOMXz')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->deleteAlbum('VOMXz'));
    }

    public function testFavoriteAlbum()
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('album/VOMXz/favorite')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->favoriteAlbum('VOMXz'));
    }

    public function testSetAlbumImages()
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('album/VOMXz')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->setAlbumImages('VOMXz', ['POvvB', 'P1vvB']));
    }

    public function testAddImages()
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('album/VOMXz/add')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->addImages('VOMXz', ['POvvB', 'P1vvB']));
    }

    public function testRemoveImages()
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with('album/VOMXz/remove_images')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->removeImages('VOMXz', ['POvvB', 'P1vvB']));
    }

    protected function getApiClass()
    {
        return 'Imgur\Api\Album';
    }
}
