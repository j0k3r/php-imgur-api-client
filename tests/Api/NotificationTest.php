<?php

namespace Imgur\tests\Api;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Subscriber\Mock;
use Imgur\Api\Notification;
use Imgur\Client;
use Imgur\HttpClient\HttpClient;

class NotificationTest extends ApiTestCase
{
    protected function getApiClass()
    {
        return 'Imgur\Api\Notification';
    }

    /**
     * @expectedException Imgur\Exception\ErrorException
     * @expectedExceptionMessage Authentication required
     */
    public function testBaseReal()
    {
        $guzzleClient = new GuzzleClient(['base_url' => 'https://api.imgur.com/3/']);
        $httpClient = new HttpClient([], $guzzleClient);
        $client = new Client(null, $httpClient);
        $notification = new Notification($client);

        $notification->notifications();
    }

    public function testBaseWithResponse()
    {
        $guzzleClient = new GuzzleClient();
        $mock = new Mock([
            new Response(200, ['Content-Type' => 'application/json'], Stream::factory(json_encode([
                'data' => [
                    'replies' => [],
                    'messages' => [
                        [
                            'id' => 76878181,
                            'account_id' => 703051,
                            'viewed' => true,
                            'content' => [
                                'id' => '11247233',
                                'account_id' => '703051',
                                'with_account' => '48',
                                'spam' => '0',
                                'message_num' => '2',
                                'last_message' => 'You were awarded the 2 Years Trophy!

                                Happy Anniversary! You and Imgur have been together for 2 years, and we\'re still so in love with you!

                                Check out your gallery profile page to see it in all its majesty!',
                                'from' => 'imgur',
                                'datetime' => 1390592168,
                            ],
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
        $notification = new Notification($client);

        $result = $notification->notifications();

        $this->assertArrayHasKey('replies', $result);
        $this->assertArrayHasKey('messages', $result);
        $this->assertArrayHasKey('id', $result['messages'][0]);
        $this->assertArrayHasKey('account_id', $result['messages'][0]);
        $this->assertArrayHasKey('viewed', $result['messages'][0]);
        $this->assertArrayHasKey('content', $result['messages'][0]);
        $this->assertArrayHasKey('id', $result['messages'][0]['content']);
        $this->assertArrayHasKey('account_id', $result['messages'][0]['content']);
        $this->assertArrayHasKey('with_account', $result['messages'][0]['content']);
        $this->assertArrayHasKey('spam', $result['messages'][0]['content']);
        $this->assertArrayHasKey('message_num', $result['messages'][0]['content']);
        $this->assertArrayHasKey('last_message', $result['messages'][0]['content']);
        $this->assertArrayHasKey('from', $result['messages'][0]['content']);
        $this->assertArrayHasKey('datetime', $result['messages'][0]['content']);
    }

    public function testNotifications()
    {
        $expectedValue = [
            'data' => [
                'replies' => [],
                'messages' => [],
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('notification', ['new' => 'true'])
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->notifications());
    }

    public function testNotification()
    {
        $expectedValue = [
            'data' => [
                'id' => '76878181',
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('notification/76878181')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->notification('76878181'));
    }

    public function testNotificationViewed()
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('notification/76878181')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->notificationViewed('76878181'));
    }
}
