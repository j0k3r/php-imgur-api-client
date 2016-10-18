<?php

namespace Imgur\HttpClient;

/**
 * Basic client for performing HTTP requests.
 *
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */
interface HttpClientInterface
{
    /**
     * Perform a GET request.
     *
     * @param string $url        URL to which the request should point
     * @param array  $parameters Request parameters
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function get($url, array $parameters = []);

    /**
     * Perform a POST request.
     *
     * @param string $url        URL to which the request should point
     * @param array  $parameters Request parameters
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function post($url, array $parameters = []);

    /**
     * Perform a DELETE request.
     *
     * @param string $url        URL to which the request should point
     * @param array  $parameters Request parameters
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function delete($url, array $parameters = []);

    /**
     * Perform the actual request.
     *
     * @param string $url        URL to which the request should point
     * @param array  $parameters Request parameters
     * @param string $httpMethod HTTP method to use
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function performRequest($url, $parameters, $httpMethod = 'GET');

    /**
     * Parses the Imgur server response.
     *
     * @param object $response
     *
     * @return array
     */
    public function parseResponse($response);
}
