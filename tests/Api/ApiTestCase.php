<?php

namespace Imgur\tests\Api;

use Imgur\Client;
use PHPUnit\Framework\TestCase;

abstract class ApiTestCase extends TestCase
{
    abstract protected function getApiClass();

    protected function getApiMock()
    {
        $httpClient = $this->getMockBuilder('Imgur\HttpClient\HttpClient')
            ->disableOriginalConstructor()
            ->getMock();
        $httpClient
            ->expects($this->any())
            ->method('get');
        $httpClient
            ->expects($this->any())
            ->method('delete');
        $httpClient
            ->expects($this->any())
            ->method('post');
        $httpClient
            ->expects($this->any())
            ->method('put');

        $client = new Client(null, $httpClient);

        return $this->getMockBuilder($this->getApiClass())
            ->setMethods(['get', 'post', 'put', 'delete'])
            ->setConstructorArgs([$client])
            ->getMock();
    }
}
