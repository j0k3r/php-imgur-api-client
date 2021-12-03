<?php

namespace Imgur\tests\Api;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Imgur\Api\AlbumOrImage;
use Imgur\Client;
use Imgur\Exception\ErrorException;
use Imgur\HttpClient\HttpClient;
use PHPUnit\Framework\TestCase;

class AlbumOrImageTest extends TestCase
{
    public function testWithImageId(): void
    {
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], (string) json_encode(['data' => ['ok !']])),
        ]);
        $handler = HandlerStack::create($mock);
        $client = new GuzzleClient(['handler' => $handler]);

        $httpClient = new HttpClient([], $client, $handler);

        $client = new Client(null, $httpClient);
        $api = new AlbumOrImage($client);

        $this->assertSame(['ok !'], $api->find('ZOY11VC'));
    }

    public function testWithAlbumId(): void
    {
        $mock = new MockHandler([
            new Response(404, ['Content-Type' => 'application/json'], (string) json_encode([
                'data' => [
                    'error' => 'Unable to find an image with the id, 8pCqe',
                    'request' => '/3/image/8pCqe',
                    'method' => 'GET',
                ],
                'success' => false,
                'status' => 404,
            ])),
            new Response(200, ['Content-Type' => 'application/json'], (string) json_encode(['data' => ['ok !']])),
        ]);
        $handler = HandlerStack::create($mock);
        $client = new GuzzleClient(['handler' => $handler]);

        $httpClient = new HttpClient([], $client, $handler);

        $client = new Client(null, $httpClient);
        $api = new AlbumOrImage($client);

        $this->assertSame(['ok !'], $api->find('8pCqe'));
    }

    public function testWithBadId(): void
    {
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('id');

        $mock = new MockHandler([
            new Response(404, ['Content-Type' => 'application/json'], (string) json_encode([
                'data' => [
                    'error' => 'Unable to find an image with the id, xxxxxxx',
                    'request' => '/3/image/xxxxxxx',
                    'method' => 'GET',
                ],
                'success' => false,
                'status' => 404,
            ])),
            new Response(404, ['Content-Type' => 'application/json'], (string) json_encode([
                'data' => [
                    'error' => 'Unable to find an album with the id, xxxxxxx',
                    'request' => '/3/album/xxxxxxx',
                    'method' => 'GET',
                ],
                'success' => false,
                'status' => 404,
            ])),
        ]);
        $handler = HandlerStack::create($mock);
        $client = new GuzzleClient(['handler' => $handler]);

        $httpClient = new HttpClient([], $client, $handler);

        $client = new Client(null, $httpClient);
        $api = new AlbumOrImage($client);

        $api->find('xxxxxxx');
    }

    public function testWithImageIdButBadResponse(): void
    {
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('oops');

        $mock = new MockHandler([
            new Response(500, ['Content-Type' => 'application/json'], (string) json_encode([
                'data' => [
                    'error' => 'oops !',
                    'request' => '/3/image/xxxxxxx',
                    'method' => 'GET',
                ],
                'success' => false,
                'status' => 404,
            ])),
        ]);
        $handler = HandlerStack::create($mock);
        $client = new GuzzleClient(['handler' => $handler]);

        $httpClient = new HttpClient([], $client, $handler);

        $client = new Client(null, $httpClient);
        $api = new AlbumOrImage($client);

        $api->find('ZOY11VC');
    }

    public function testWithAlbumIdButBadResponse(): void
    {
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('oops');

        $mock = new MockHandler([
            new Response(404, ['Content-Type' => 'application/json'], (string) json_encode([
                'data' => [
                    'error' => 'Unable to find an image with the id, xxxxxxx',
                    'request' => '/3/image/xxxxxxx',
                    'method' => 'GET',
                ],
                'success' => false,
                'status' => 404,
            ])),
            new Response(500, ['Content-Type' => 'application/json'], (string) json_encode([
                'data' => [
                    'error' => 'oops !',
                    'request' => '/3/image/xxxxxxx',
                    'method' => 'GET',
                ],
                'success' => false,
                'status' => 404,
            ])),
        ]);
        $handler = HandlerStack::create($mock);
        $client = new GuzzleClient(['handler' => $handler]);

        $httpClient = new HttpClient([], $client, $handler);

        $client = new Client(null, $httpClient);
        $api = new AlbumOrImage($client);

        $api->find('8pCqe');
    }
}
