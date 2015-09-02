<?php

namespace Tests\Imgur;

use Imgur\Client;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructDefault()
    {
        $client = new Client();
    }
}
