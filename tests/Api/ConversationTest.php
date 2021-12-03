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
    public function testBaseReal(): void
    {
        $this->expectException(\Imgur\Exception\ErrorException::class);
        $this->expectExceptionMessage('Authentication required');

        $httpClient = new HttpClient();
        $client = new Client(null, $httpClient);
        $conversation = new Conversation($client);

        $conversation->conversations();
    }

    public function testBaseWithResponse(): void
    {
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], (string) json_encode([
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

        $httpClient = new HttpClient([], $guzzleClient, $handler);
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

    public function testConversations(): void
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

        $api = $this->getApiConversationMock();
        $api->expects($this->once())
            ->method('get')
            ->with('conversations')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->conversations());
    }

    public function testConversation(): void
    {
        $expectedValue = [
            'data' => [
                'id' => 11247233,
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiConversationMock();
        $api->expects($this->once())
            ->method('get')
            ->with('conversations/11247233')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->conversation('11247233'));
    }

    public function testMessageCreate(): void
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiConversationMock();
        $api->expects($this->once())
            ->method('post')
            ->with('conversations/imgur')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->messageCreate(['recipient' => 'imgur', 'body' => 'YO !']));
    }

    public function testMessageCreateParamMissing(): void
    {
        $this->expectException(\Imgur\Exception\MissingArgumentException::class);
        $this->expectExceptionMessage('parameters is missing');

        $this->getApiConversationMock()->messageCreate([]);
    }

    public function testConversationDelete(): void
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiConversationMock();
        $api->expects($this->once())
            ->method('delete')
            ->with('conversations/11247233')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->conversationDelete('11247233'));
    }

    public function testReportSender(): void
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiConversationMock();
        $api->expects($this->once())
            ->method('post')
            ->with('conversations/report/imgur')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->reportSender('imgur'));
    }

    public function testBlockSender(): void
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiConversationMock();
        $api->expects($this->once())
            ->method('post')
            ->with('conversations/block/imgur')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->blockSender('imgur'));
    }
}
