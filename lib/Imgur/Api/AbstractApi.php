<?php

namespace Imgur\Api;

use Imgur\Client;

/**
 * Abstract class supporting API requests
 * 
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */

abstract class AbstractApi {
    
    /**
     * 
     * @var Imgur\Client
     */
    private $client;
    
    public function __construct($client) {
        $this->client = $client;
    }
    
    /**
     * Perform a GET request and return the parsed response
     * 
     * @param string $url
     * @return array
     */
    public function get($url, $parameters = array()) {
        $httpClient = $this->client->getHttpClient();
        
        $response = $httpClient->get($url, $parameters);
        
        return $httpClient->parseResponse($response);
    }
    
    /**
     * Perform a POST request and return the parsed response
     * 
     * @param string $url
     * @return array
     */
    public function post($url, $parameters = array()) {
        $httpClient = $this->client->getHttpClient();
        
        $response = $httpClient->post($url, $parameters);
        
        return $httpClient->parseResponse($response);
    }
    
    /**
     * Perform a DELETE request and return the parsed response
     * 
     * @param string $url
     * @return array
     */
    public function delete($url, $parameters = array()) {
        $httpClient = $this->client->getHttpClient();
        
        $response = $httpClient->delete($url, $parameters);
        
        return $httpClient->parseResponse($response);
    }    
}