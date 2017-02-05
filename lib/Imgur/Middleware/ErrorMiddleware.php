<?php

namespace Imgur\Middleware;

use Imgur\Exception\ErrorException;
use Imgur\Exception\RateLimitException;
use Imgur\Exception\RuntimeException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ErrorMiddleware
{
    private $nextHandler;

    /**
     * @param callable $nextHandler Next handler to invoke
     */
    public function __construct(callable $nextHandler)
    {
        $this->nextHandler = $nextHandler;
    }

    /**
     * @param RequestInterface $request
     * @param array            $options
     *
     * @return PromiseInterface
     */
    public function __invoke(RequestInterface $request, array $options)
    {
        $fn = $this->nextHandler;

        return $fn($request, $options)
            ->then(function (ResponseInterface $response) {
                return $this->checkError($response);
            });
    }

    /**
     * Middleware that handles rate limit errors.
     *
     * @return \Closure Returns a function that accepts the next handler
     */
    public static function error()
    {
        return function (callable $handler) {
            return new self($handler);
        };
    }

    /**
     * Check for an error.
     *
     * @param ResponseInterface $response
     */
    public function checkError(ResponseInterface $response)
    {
        if ($response->getStatusCode() < 400) {
            return $response;
        }

        //check if any limit was hit
        $this->checkUserRateLimit($response);
        $this->checkClientRateLimit($response);
        $this->checkPostRateLimit($response);

        $body = (string) $response->getBody();
        $responseData = json_decode($body, true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            $responseData = $body;
        }

        if (is_array($responseData) && isset($responseData['data']) && isset($responseData['data']['error'])) {
            throw new ErrorException(
                'Request to: ' . $responseData['data']['request'] . ' failed with: "' . $responseData['data']['error'] . '"',
                $response->getStatusCode()
            );
        }

        throw new RuntimeException(is_array($responseData) && isset($responseData['message']) ? $responseData['message'] : $responseData, $response->getStatusCode());
    }

    /**
     * Check if user hit limit.
     *
     * @param ResponseInterface $response
     */
    private function checkUserRateLimit(ResponseInterface $response)
    {
        $userRemaining = $response->getHeaderLine('X-RateLimit-UserRemaining');
        $userLimit = $response->getHeaderLine('X-RateLimit-UserLimit');

        if ('' !== $userRemaining && $userRemaining < 1) {
            throw new RateLimitException('No user credits available. The limit is ' . $userLimit);
        }
    }

    /**
     * Check if client hit limit.
     *
     * @param ResponseInterface $response
     */
    private function checkClientRateLimit(ResponseInterface $response)
    {
        $clientRemaining = $response->getHeaderLine('X-RateLimit-ClientRemaining');
        $clientLimit = $response->getHeaderLine('X-RateLimit-ClientLimit');

        if ('' !== $clientRemaining && $clientRemaining < 1) {
            // X-RateLimit-UserReset: Timestamp (unix epoch) for when the credits will be reset.
            $resetTime = date('Y-m-d H:i:s', $response->getHeaderLine('X-RateLimit-UserReset'));

            throw new RateLimitException('No application credits available. The limit is ' . $clientLimit . ' and will be reset at ' . $resetTime);
        }
    }

    /**
     * Check if client hit post limit.
     *
     * @param ResponseInterface $response
     */
    private function checkPostRateLimit(ResponseInterface $response)
    {
        $postRemaining = $response->getHeaderLine('X-Post-Rate-Limit-Remaining');
        $postLimit = $response->getHeaderLine('X-Post-Rate-Limit-Limit');

        if ('' !== $postRemaining && $postRemaining < 1) {
            // X-Post-Rate-Limit-Reset: Time in seconds until your POST ratelimit is reset
            $resetTime = date('Y-m-d H:i:s', $response->getHeaderLine('X-Post-Rate-Limit-Reset'));

            throw new RateLimitException('No post credits available. The limit is ' . $postLimit . ' and will be reset at ' . $resetTime);
        }
    }
}
