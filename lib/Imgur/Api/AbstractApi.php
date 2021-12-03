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
     * @var Client
     */
    protected $client;

    /**
     * @var PagerInterface
     */
    protected $pager;

    public function __construct(Client $client, PagerInterface $pager = null)
    {
        $this->client = $client;
        $this->pager = $pager;
    }

    /**
     * Perform a GET request and return the parsed response.
     */
    public function get(string $url, array $parameters = []): array
    {
        $httpClient = $this->client->getHttpClient();

        if (null !== $this->pager) {
            $parameters['page'] = $this->pager->getPage();
            $parameters['perPage'] = $this->pager->getResultsPerPage();
        }

        $response = $httpClient->get($url, ['query' => $parameters]);

        return $httpClient->parseResponse($response);
    }

    /**
     * Perform a POST request and return the parsed response.
     */
    public function post(string $url, array $parameters = []): array
    {
        $httpClient = $this->client->getHttpClient();

        $response = $httpClient->post($url, $parameters);

        return $httpClient->parseResponse($response);
    }

    /**
     * Perform a PUT request and return the parsed response.
     */
    public function put(string $url, array $parameters = []): array
    {
        $httpClient = $this->client->getHttpClient();

        $response = $httpClient->put($url, $parameters);

        return $httpClient->parseResponse($response);
    }

    /**
     * Perform a DELETE request and return the parsed response.
     */
    public function delete(string $url, array $parameters = []): array
    {
        $httpClient = $this->client->getHttpClient();

        $response = $httpClient->delete($url, $parameters);

        return $httpClient->parseResponse($response);
    }

    /**
     * Validate "sort" parameter and throw an exception if it's a bad value.
     *
     * @param string $sort Input value
     */
    protected function validateSortArgument(string $sort, array $possibleValues): void
    {
        $this->validateArgument('Sort', $sort, $possibleValues);
    }

    /**
     * Validate "window" parameter and throw an exception if it's a bad value.
     *
     * @param string $window Input value
     */
    protected function validateWindowArgument(string $window, array $possibleValues): void
    {
        $this->validateArgument('Window', $window, $possibleValues);
    }

    /**
     * Validate "vote" parameter and throw an exception if it's a bad value.
     *
     * @param string $vote Input value
     */
    protected function validateVoteArgument(string $vote, array $possibleValues): void
    {
        $this->validateArgument('Vote', $vote, $possibleValues);
    }

    /**
     * Global method to validate an argument.
     *
     * @param string $type           The required parameter (used for the error message)
     * @param string $input          Input value
     * @param array  $possibleValues Possible values for this argument
     */
    private function validateArgument(string $type, string $input, array $possibleValues): void
    {
        if (!\in_array($input, $possibleValues, true)) {
            throw new InvalidArgumentException($type . ' parameter "' . $input . '" is wrong. Possible values are: ' . implode(', ', $possibleValues));
        }
    }
}
