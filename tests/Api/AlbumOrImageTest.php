<?php

namespace Imgur\tests\Api;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Subscriber\Mock;
use Imgur\Api\AlbumOrImage;
use Imgur\Client;
use Imgur\HttpClient\HttpClient;

class AlbumOrImageTest extends \PHPUnit_Framework_TestCase
{
    public function testWithImageId()
    {
        $client = new GuzzleClient();
        $mock = new Mock([
            new Response(200, ['Content-Type' => 'application/json'], Stream::factory(json_encode(['data' => 'ok !']))),
        ]);
        $client->getEmitter()->attach($mock);

        $httpClient = new HttpClient([], $client);

        $client = new Client(null, $httpClient);
        $api = new AlbumOrImage($client);

        $this->assertSame('ok !', $api->find('ZOY11VC'));
    }

    public function testWithAlbumId()
    {
        $client = new GuzzleClient();
        $mock = new Mock([
            new Response(404, ['Content-Type' => 'application/json'], Stream::factory(json_encode([
                'data' => [
                    'error' => 'Unable to find an image with the id, 8pCqe',
                    'request' => '/3/image/8pCqe',
                    'method' => 'GET',
                ],
                'success' => false,
                'status' => 404,
            ]))),
            new Response(200, ['Content-Type' => 'application/json'], Stream::factory(json_encode(['data' => 'ok !']))),
        ]);
        $client->getEmitter()->attach($mock);

        $httpClient = new HttpClient([], $client);

        $client = new Client(null, $httpClient);
        $api = new AlbumOrImage($client);

        $this->assertSame('ok !', $api->find('8pCqe'));
    }

    /**
     * @expectedException \Imgur\Exception\ErrorException
     * @expectedExceptionMessage Unable to find an album OR an image with the id
     */
    public function testWithBadId()
    {
        $client = new GuzzleClient();
        $mock = new Mock([
            new Response(404, ['Content-Type' => 'application/json'], Stream::factory(json_encode([
                'data' => [
                    'error' => 'Unable to find an image with the id, xxxxxxx',
                    'request' => '/3/image/xxxxxxx',
                    'method' => 'GET',
                ],
                'success' => false,
                'status' => 404,
            ]))),
            new Response(404, ['Content-Type' => 'application/json'], Stream::factory(json_encode([
                'data' => [
                    'error' => 'Unable to find an album with the id, xxxxxxx',
                    'request' => '/3/album/xxxxxxx',
                    'method' => 'GET',
                ],
                'success' => false,
                'status' => 404,
            ]))),
        ]);
        $client->getEmitter()->attach($mock);

        $httpClient = new HttpClient([], $client);

        $client = new Client(null, $httpClient);
        $api = new AlbumOrImage($client);

        $api->find('xxxxxxx');
    }

    /**
     * @expectedException \Imgur\Exception\ErrorException
     * @expectedExceptionMessage oops
     */
    public function testWithImageIdButBadResponse()
    {
        $client = new GuzzleClient();
        $mock = new Mock([
            new Response(500, ['Content-Type' => 'application/json'], Stream::factory(json_encode([
                'data' => [
                    'error' => 'oops !',
                    'request' => '/3/image/xxxxxxx',
                    'method' => 'GET',
                ],
                'success' => false,
                'status' => 404,
            ]))),
        ]);
        $client->getEmitter()->attach($mock);

        $httpClient = new HttpClient([], $client);

        $client = new Client(null, $httpClient);
        $api = new AlbumOrImage($client);

        $api->find('ZOY11VC');
    }

    /**
     * @expectedException \Imgur\Exception\ErrorException
     * @expectedExceptionMessage oops
     */
    public function testWithAlbumIdButBadResponse()
    {
        $client = new GuzzleClient();
        $mock = new Mock([
            new Response(404, ['Content-Type' => 'application/json'], Stream::factory(json_encode([
                'data' => [
                    'error' => 'Unable to find an image with the id, xxxxxxx',
                    'request' => '/3/image/xxxxxxx',
                    'method' => 'GET',
                ],
                'success' => false,
                'status' => 404,
            ]))),
            new Response(500, ['Content-Type' => 'application/json'], Stream::factory(json_encode([
                'data' => [
                    'error' => 'oops !',
                    'request' => '/3/image/xxxxxxx',
                    'method' => 'GET',
                ],
                'success' => false,
                'status' => 404,
            ]))),
        ]);
        $client->getEmitter()->attach($mock);

        $httpClient = new HttpClient([], $client);

        $client = new Client(null, $httpClient);
        $api = new AlbumOrImage($client);

        $api->find('8pCqe');
    }
}
