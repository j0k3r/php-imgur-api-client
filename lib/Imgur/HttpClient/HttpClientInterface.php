<?php

namespace Imgur\HttpClient;

/**
 * Basic client for performing HTTP requests
 *
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */
interface HttpClientInterface {
    /**
     * Perform a GET request
     *
     * @param string $url         URL to which the request should point
     * @param array  $parameters  Request parameters
     *
     * @return array 
     */
    public function get($url, array $parameters = array());

    /**
     * Perform a POST request
     *
     * @param string $url         URL to which the request should point
     * @param array  $parameters  Request parameters
     *
     * @return array
     */
    public function post($url, array $parameters = array());

    /**
     * Perform the actual request
     *
     * @param string $url         URL to which the request should point
     * @param array  $parameters  Request parameters
     * @param string $httpMethod  HTTP method to use
     *
     * @return array
     */
    public function performRequest($url, $parameters, $httpMethod = 'GET');    
}
