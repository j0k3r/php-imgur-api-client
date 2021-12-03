<?php

namespace Imgur\tests\Api;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Imgur\Api\Topic;
use Imgur\Client;
use Imgur\HttpClient\HttpClient;

class TopicTest extends ApiTestCase
{
    public function testBaseReal(): void
    {
        $this->expectException(\Imgur\Exception\ErrorException::class);
        $this->expectExceptionMessage('Authentication required');

        $this->markTestSkipped('Topic endpoint does not always return 401 with no authentication ...');

        $httpClient = new HttpClient();
        $client = new Client(null, $httpClient);
        $topic = new Topic($client);

        $topic->defaultTopics();
    }

    public function testBaseWithResponse(): void
    {
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], (string) json_encode([
                'data' => [
                    [
                        'id' => 155,
                        'name' => 'Caturday',
                        'description' => 'Celebrating our feline friends.',
                        'css' => 'blahblahblah',
                        'ephemeral' => true,
                        'readonly' => false,
                        'topPost' => [
                            'id' => 'HNCRz',
                            'title' => 'Cats and Shakespearean insults',
                            'description' => 'Cats and Shakespearean sass good night imgur',
                            'datetime' => 1474847399,
                            'cover' => 'ExOlCds',
                            'cover_width' => 599,
                            'cover_height' => 376,
                            'account_url' => 'CaptainCokecan',
                            'account_id' => 10186255,
                            'privacy' => 'hidden',
                            'layout' => 'blog',
                            'views' => 0,
                            'link' => 'http://imgur.com/a/HNCRz',
                            'ups' => 1283,
                            'downs' => 34,
                            'points' => 1249,
                            'score' => 1249,
                            'is_album' => true,
                            'vote' => null,
                            'favorite' => null,
                            'nsfw' => false,
                            'section' => '',
                            'comment_count' => 29,
                            'topic' => 'Caturday',
                            'topic_id' => 155,
                            'images_count' => 23,
                            'in_gallery' => true,
                            'is_ad' => false,
                        ],
                        'heroImage' => [
                            'id' => 'gUu7DeO',
                            'title' => null,
                            'description' => null,
                            'datetime' => 1468093573,
                            'type' => 'image/jpeg',
                            'animated' => false,
                            'width' => 800,
                            'height' => 600,
                            'size' => 56077,
                            'views' => 3117423,
                            'bandwidth' => 174815729571,
                            'vote' => null,
                            'favorite' => false,
                            'nsfw' => null,
                            'section' => null,
                            'account_url' => null,
                            'account_id' => 4473704,
                            'is_ad' => false,
                            'in_gallery' => true,
                            'link' => 'http://i.imgur.com/gUu7DeO.jpg',
                            'comment_count' => null,
                            'ups' => null,
                            'downs' => null,
                            'points' => null,
                            'score' => null,
                            'is_album' => false,
                        ],
                        'isHero' => true,
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
        $topic = new Topic($client);

        $result = $topic->defaultTopics();

        $this->assertArrayHasKey('id', $result[0]);
        $this->assertArrayHasKey('name', $result[0]);
        $this->assertArrayHasKey('description', $result[0]);
        $this->assertArrayHasKey('css', $result[0]);
        $this->assertArrayHasKey('ephemeral', $result[0]);
        $this->assertArrayHasKey('readonly', $result[0]);
        $this->assertArrayHasKey('topPost', $result[0]);
        $this->assertArrayHasKey('heroImage', $result[0]);
        $this->assertArrayHasKey('isHero', $result[0]);
    }

    public function testTopic(): void
    {
        $expectedValue = [
            'data' => [
                'id' => 'ZOY11VC',
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiTopicMock();
        $api->expects($this->once())
            ->method('get')
            ->with('topics/defaults')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->defaultTopics());
    }

    public function testGalleryTopic(): void
    {
        $expectedValue = [
            'data' => [
                'id' => 'HNCRz',
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiTopicMock();
        $api->expects($this->once())
            ->method('get')
            ->with('topics/155/viral/week/0')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->galleryTopic('155'));
    }

    public function testGalleryTopicWrongSortValue(): void
    {
        $this->expectException(\Imgur\Exception\InvalidArgumentException::class);
        $this->expectExceptionMessage('is wrong. Possible values are');

        $this->getApiTopicMock()->galleryTopic('155', 'bad sort');
    }

    public function testGalleryTopicWrongWindowValue(): void
    {
        $this->expectException(\Imgur\Exception\InvalidArgumentException::class);
        $this->expectExceptionMessage('is wrong. Possible values are');

        $this->getApiTopicMock()->galleryTopic('155', 'viral', 0, 'bad window');
    }

    public function testGalleryTopicItem(): void
    {
        $expectedValue = [
            'data' => [
                'id' => 'Fbae9SG',
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiTopicMock();
        $api->expects($this->once())
            ->method('get')
            ->with('topics/155/Fbae9SG')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->galleryTopicItem('155', 'Fbae9SG'));
    }
}
