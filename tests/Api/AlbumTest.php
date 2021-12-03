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
    public function testBaseReal(): void
    {
        $this->expectException(\Imgur\Exception\ErrorException::class);
        $this->expectExceptionMessage('Authentication required');

        $httpClient = new HttpClient();
        $client = new Client(null, $httpClient);
        $album = new Album($client);

        $album->album('VOMXz');
    }

    public function testBaseWithResponse(): void
    {
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], (string) json_encode([
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

        $httpClient = new HttpClient([], $guzzleClient, $handler);
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

    public function testAlbum(): void
    {
        $expectedValue = [
            'data' => [
                'id' => 'VOMXz',
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiAlbumMock();
        $api->expects($this->once())
            ->method('get')
            ->with('album/VOMXz')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->album('VOMXz'));
    }

    public function testAlbumImages(): void
    {
        $expectedValue = [
            'data' => [
                'id' => 'VOMXz',
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiAlbumMock();
        $api->expects($this->once())
            ->method('get')
            ->with('album/VOMXz/images')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->albumImages('VOMXz'));
    }

    public function testAlbumImage(): void
    {
        $expectedValue = [
            'data' => [
                'id' => 'POvvB',
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiAlbumMock();
        $api->expects($this->once())
            ->method('get')
            ->with('album/VOMXz/image/POvvB')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->albumImage('VOMXz', 'POvvB'));
    }

    public function testCreate(): void
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiAlbumMock();
        $api->expects($this->once())
            ->method('post')
            ->with('album')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->create(['VOMXz']));
    }

    public function testUpdate(): void
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiAlbumMock();
        $api->expects($this->once())
            ->method('post')
            ->with('album/VOMXz')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->update('VOMXz', [
            'title' => 'New title',
        ]));
    }

    public function testDeleteAlbum(): void
    {
        $expectedValue = [
            'data' => [
                'id' => 'VOMXz',
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiAlbumMock();
        $api->expects($this->once())
            ->method('delete')
            ->with('album/VOMXz')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->deleteAlbum('VOMXz'));
    }

    public function testFavoriteAlbum(): void
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiAlbumMock();
        $api->expects($this->once())
            ->method('post')
            ->with('album/VOMXz/favorite')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->favoriteAlbum('VOMXz'));
    }

    public function testSetAlbumImages(): void
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiAlbumMock();
        $api->expects($this->once())
            ->method('post')
            ->with('album/VOMXz')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->setAlbumImages('VOMXz', ['POvvB', 'P1vvB']));
    }

    public function testAddImages(): void
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiAlbumMock();
        $api->expects($this->once())
            ->method('post')
            ->with('album/VOMXz/add')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->addImages('VOMXz', ['POvvB', 'P1vvB']));
    }

    public function testRemoveImages(): void
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiAlbumMock();
        $api->expects($this->once())
            ->method('delete')
            ->with('album/VOMXz/remove_images')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->removeImages('VOMXz', ['POvvB', 'P1vvB']));
    }
}
