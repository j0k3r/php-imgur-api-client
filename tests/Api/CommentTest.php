<?php

namespace Imgur\tests\Api;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Imgur\Api\Comment;
use Imgur\Client;
use Imgur\HttpClient\HttpClient;

class CommentTest extends ApiTestCase
{
    protected function getApiClass()
    {
        return 'Imgur\Api\Comment';
    }

    /**
     * @expectedException \Imgur\Exception\ErrorException
     * @expectedExceptionMessage Authentication required
     */
    public function testBaseReal()
    {
        $guzzleClient = new GuzzleClient(['base_uri' => 'https://api.imgur.com/3/']);
        $httpClient = new HttpClient([], $guzzleClient);
        $client = new Client(null, $httpClient);
        $comment = new Comment($client);

        $comment->comment('726305564');
    }

    public function testBaseWithResponse()
    {
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'data' => [
                    'id' => 726305564,
                    'image_id' => 'XVO7F',
                    'comment' => 'Scary !',
                    'author' => 'j0k3rx',
                    'author_id' => 703058,
                    'on_album' => false,
                    'album_cover' => null,
                    'ups' => 1,
                    'downs' => 0,
                    'points' => 1,
                    'datetime' => 1474399203,
                    'parent_id' => 0,
                    'deleted' => false,
                    'vote' => 'up',
                    'platform' => 'desktop',
                    'children' => [],
                ],
                'success' => true,
                'status' => 200,
            ])),
        ]);
        $handler = HandlerStack::create($mock);
        $guzzleClient = new GuzzleClient(['handler' => $handler]);

        $httpClient = new HttpClient([], $guzzleClient);
        $client = new Client(null, $httpClient);
        $comment = new Comment($client);

        $result = $comment->comment('726305564');

        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('image_id', $result);
        $this->assertArrayHasKey('comment', $result);
        $this->assertArrayHasKey('author', $result);
        $this->assertArrayHasKey('author_id', $result);
        $this->assertArrayHasKey('on_album', $result);
        $this->assertArrayHasKey('album_cover', $result);
        $this->assertArrayHasKey('ups', $result);
        $this->assertArrayHasKey('downs', $result);
        $this->assertArrayHasKey('points', $result);
        $this->assertArrayHasKey('datetime', $result);
        $this->assertArrayHasKey('parent_id', $result);
        $this->assertArrayHasKey('deleted', $result);
        $this->assertArrayHasKey('vote', $result);
        $this->assertArrayHasKey('platform', $result);
        $this->assertArrayHasKey('children', $result);
    }

    public function testComment()
    {
        $expectedValue = [
            'data' => [
                'id' => '726305564',
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('comment/726305564')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->comment('726305564'));
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
            ->with('comment')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->create(['image_id' => 'ZOY11VC', 'comment' => 'I agree']));
    }

    /**
     * @expectedException \Imgur\Exception\MissingArgumentException
     * @expectedExceptionMessage parameters is missing
     */
    public function testCreateParamMissing()
    {
        $this->getApiMock()->create('726305564');
    }

    public function testDeleteComment()
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with('comment/726305564')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->deleteComment('726305564'));
    }

    public function testReplies()
    {
        $expectedValue = [
            'data' => [
                'id' => '726305564',
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('comment/726305564/replied')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->replies('726305564'));
    }

    public function testCreateReply()
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('comment/726305565')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->createReply('726305565', ['image_id' => 'ZOY11VC', 'comment' => 'I agree']));
    }

    /**
     * @expectedException \Imgur\Exception\MissingArgumentException
     * @expectedExceptionMessage parameters is missing
     */
    public function testCreateReplyParamMissing()
    {
        $this->getApiMock()->createReply('726305564', []);
    }

    public function testVote()
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('comment/726305564/vote/up')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->vote('726305564', 'up'));
    }

    /**
     * @expectedException \Imgur\Exception\InvalidArgumentException
     * @expectedExceptionMessage is wrong. Possible values are
     */
    public function testVoteWrongVoteValue()
    {
        $this->getApiMock()->vote('726305564', 'bad vote');
    }

    public function testReport()
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('comment/726305564/report')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->report('726305564'));
    }
}
