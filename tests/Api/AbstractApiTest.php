<?php

namespace Imgur\tests\Api;

use Imgur\Api\AbstractApi;
use Imgur\Client;
use Imgur\Pager\BasicPager;
use PHPUnit\Framework\TestCase;

class AbstractApiTest extends TestCase
{
    public function testGet(): void
    {
        $image = $this->getApiMock('get');
        $image->get('gallery/search/time/0', ['q' => '20minutes']);
    }

    public function testDelete(): void
    {
        $image = $this->getApiMock('delete');
        $image->delete('image/ZOY11VC');
    }

    public function testPost(): void
    {
        $image = $this->getApiMock('post');
        $image->post('album/VOMXz', ['title' => 'yo']);
    }

    public function testPut(): void
    {
        $image = $this->getApiMock('put');
        $image->put('album/VOMXz', ['title' => 'yo']);
    }

    protected function getApiMock(string $method): TestAbstractApi
    {
        $httpClient = $this->getMockBuilder('Imgur\HttpClient\HttpClient')
            ->disableOriginalConstructor()
            ->getMock();
        $httpClient
            ->expects($this->once())
            ->method($method);
        $httpClient
            ->expects($this->any())
            ->method('parseResponse')
            ->willReturn(['data' => true, 'success' => true, 'status' => 200]);

        $client = new Client(null, $httpClient);

        return new TestAbstractApi($client, new BasicPager());
    }
}

class TestAbstractApi extends AbstractApi
{
}
