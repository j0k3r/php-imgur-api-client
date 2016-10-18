<?php

namespace Imgur\tests\Api;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Imgur\Api\Conversation;
use Imgur\Client;
use Imgur\HttpClient\HttpClient;

class ConversationTest extends ApiTestCase
{
    protected function getApiClass()
    {
        return 'Imgur\Api\Conversation';
    }

    /**
     * @expectedException Imgur\Exception\ErrorException
     * @expectedExceptionMessage Authentication required
     */
    public function testBaseReal()
    {
        $guzzleClient = new GuzzleClient(['base_uri' => 'https://api.imgur.com/3/']);
        $httpClient = new HttpClient([], $guzzleClient);
        $client = new Client(null, $httpClient);
        $conversation = new Conversation($client);

        $conversation->conversations();
    }

    public function testBaseWithResponse()
    {
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'data' => [
                    [
                        'id' => 11247233,
                        'with_account' => 'imgur',
                        'with_account_id' => 48,
                        'last_message_preview' => 'You were awarded the 2 Years T...',
                        'message_count' => 2,
                        'datetime' => 1390592169,
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
        $conversation = new Conversation($client);

        $result = $conversation->conversations();

        $this->assertArrayHasKey('id', $result[0]);
        $this->assertArrayhasKey('with_account', $result[0]);
        $this->assertArrayhasKey('with_account_id', $result[0]);
        $this->assertArrayhasKey('last_message_preview', $result[0]);
        $this->assertArrayhasKey('message_count', $result[0]);
        $this->assertArrayhasKey('datetime', $result[0]);
    }

    public function testConversations()
    {
        $expectedValue = [
            'data' => [
                [
                    'id' => 11247233,
                ],
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('conversations')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->conversations());
    }

    public function testConversation()
    {
        $expectedValue = [
            'data' => [
                'id' => 11247233,
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('conversations/11247233')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->conversation('11247233'));
    }

    public function testMessageCreate()
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('conversations/imgur')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->messageCreate(['recipient' => 'imgur', 'body' => 'YO !']));
    }

    /**
     * @expectedException Imgur\Exception\MissingArgumentException
     * @expectedExceptionMessage parameters is missing
     */
    public function testMessageCreateParamMissing()
    {
        $this->getApiMock()->messageCreate([]);
    }

    public function testConversationDelete()
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with('conversations/11247233')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->conversationDelete('11247233'));
    }

    public function testReportSender()
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('conversations/report/imgur')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->reportSender('imgur'));
    }

    public function testBlockSender()
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('conversations/block/imgur')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->blockSender('imgur'));
    }
}
