<?php

namespace Imgur\Api;

use Imgur\Client;
use Imgur\Exception\InvalidArgumentException;
use Imgur\Pager\PagerInterface;

/**
 * Abstract class supporting API requests.
 *
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */
abstract class AbstractApi
{
    /**
     * @var \Imgur\Client
     */
    protected $client;

    /**
     * @var \Imgur\Pager\PagerInterface
     */
    protected $pager;

    public function __construct(Client $client, PagerInterface $pager = null)
    {
        $this->client = $client;
        $this->pager = $pager;
    }

    /**
     * Perform a GET request and return the parsed response.
     *
     * @param string $url
     *
     * @return array
     */
    public function get($url, $parameters = [])
    {
        $httpClient = $this->client->getHttpClient();

        if (!empty($this->pager)) {
            $parameters['page'] = $this->pager->getPage();
            $parameters['perPage'] = $this->pager->getResultsPerPage();
        }

        $response = $httpClient->get($url, ['query' => $parameters]);

        return $httpClient->parseResponse($response);
    }

    /**
     * Perform a POST request and return the parsed response.
     *
     * @param string $url
     *
     * @return array
     */
    public function post($url, $parameters = [])
    {
        $httpClient = $this->client->getHttpClient();

        $response = $httpClient->post($url, $parameters);

        return $httpClient->parseResponse($response);
    }

    /**
     * Perform a DELETE request and return the parsed response.
     *
     * @param string $url
     *
     * @return array
     */
    public function delete($url, $parameters = [])
    {
        $httpClient = $this->client->getHttpClient();

        $response = $httpClient->delete($url, $parameters);

        return $httpClient->parseResponse($response);
    }

    /**
     * Validate "sort" parameter and throw an exception if it's a bad value.
     *
     * @param string $sort           Input value
     * @param array  $possibleValues
     */
    protected function validateSortArgument($sort, $possibleValues)
    {
        if (!in_array($sort, $possibleValues, true)) {
            throw new InvalidArgumentException('Sort parameter "' . $sort . '" is wrong. Possible values are: ' . implode(', ', $possibleValues));
        }
    }
}
