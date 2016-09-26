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
     * Perform a PUT request and return the parsed response.
     *
     * @param string $url
     *
     * @return array
     */
    public function put($url, $parameters = [])
    {
        $httpClient = $this->client->getHttpClient();

        $response = $httpClient->put($url, $parameters);

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
     * Global method to validate an argument.
     *
     * @param string $type           The required parameter (used for the error message)
     * @param string $input          Input value
     * @param array  $possibleValues Possible values for this argument
     */
    private function validateArgument($type, $input, $possibleValues)
    {
        if (!in_array($input, $possibleValues, true)) {
            throw new InvalidArgumentException($type . ' parameter "' . $input . '" is wrong. Possible values are: ' . implode(', ', $possibleValues));
        }
    }

    /**
     * Validate "sort" parameter and throw an exception if it's a bad value.
     *
     * @param string $sort           Input value
     * @param array  $possibleValues
     */
    protected function validateSortArgument($sort, $possibleValues)
    {
        $this->validateArgument('Sort', $sort, $possibleValues);
    }

    /**
     * Validate "window" parameter and throw an exception if it's a bad value.
     *
     * @param string $window         Input value
     * @param array  $possibleValues
     */
    protected function validateWindowArgument($window, $possibleValues)
    {
        $this->validateArgument('Window', $window, $possibleValues);
    }

    /**
     * Validate "vote" parameter and throw an exception if it's a bad value.
     *
     * @param string $vote           Input value
     * @param array  $possibleValues
     */
    protected function validateVoteArgument($vote, $possibleValues)
    {
        $this->validateArgument('Vote', $vote, $possibleValues);
    }
}
