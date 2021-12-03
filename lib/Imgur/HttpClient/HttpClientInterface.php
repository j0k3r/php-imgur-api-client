<?php

namespace Imgur\HttpClient;

use Psr\Http\Message\ResponseInterface;

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
     */
    public function get($url, array $parameters = []): ResponseInterface;

    /**
     * Perform a POST request.
     *
     * @param string $url        URL to which the request should point
     * @param array  $parameters Request parameters
     */
    public function post($url, array $parameters = []): ResponseInterface;

    /**
     * Perform a DELETE request.
     *
     * @param string $url        URL to which the request should point
     * @param array  $parameters Request parameters
     */
    public function delete($url, array $parameters = []): ResponseInterface;

    /**
     * Perform a PUT request.
     *
     * @param string $url        URL to which the request should point
     * @param array  $parameters Request parameters
     */
    public function put($url, array $parameters = []): ResponseInterface;

    /**
     * Perform the actual request.
     *
     * @param string $url        URL to which the request should point
     * @param array  $parameters Request parameters
     * @param string $httpMethod HTTP method to use
     */
    public function performRequest($url, $parameters, $httpMethod = 'GET'): ResponseInterface;

    /**
     * Parses the Imgur server response.
     */
    public function parseResponse(ResponseInterface $response): array;

    /**
     * Push authorization middleware.
     *
     * @param array|null $token
     */
    public function addAuthMiddleware($token, string $clientId): void;
}
