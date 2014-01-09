<?php

namespace Imgur\HttpClient;

require_once __DIR__.'/HttpClientInterface.php';

use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Http\ClientInterface;
use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\Response;

/**
 * Basic client for performing HTTP requests
 *
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */
class HttpClient implements \Imgur\HttpClient\HttpClientInterface {

    /**
     * {@inheritDoc}
     */    
    public function get($url, array $parameters = array()) {
        
        return $this->performRequest($url, $parameters, 'GET');
    }
    
    /**
     * {@inheritDoc}
     */
    public function post($url, array $parameters = array()) {

        return $this->performRequest($url, $parameters, 'POST');
    }
    
    /**
     * {@inheritDoc}
     */
    public function performRequest($url, $parameters, $httpMethod = 'GET') {
        
    }

}