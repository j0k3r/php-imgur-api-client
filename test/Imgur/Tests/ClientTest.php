<?php

namespace Imgur\Tests;

use Imgur\Client;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldNotHaveToPassHttpClientToConstructor()
    {
        $client = new Client();

        $this->assertInstanceOf('Imgur\HttpClient\HttpClient', $client->getHttpClient());
    }
}