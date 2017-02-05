<?php

namespace Imgur\Listener;

use GuzzleHttp\Event\ErrorEvent;
use GuzzleHttp\Message\ResponseInterface;
use Imgur\Exception\ErrorException;
use Imgur\Exception\RateLimitException;
use Imgur\Exception\RuntimeException;

/**
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */
class ErrorListener
{
    public function error(ErrorEvent $event)
    {
        $response = $event->getResponse();

        if (null === $response || $response->getStatusCode() < 400) {
            return;
        }

        //check if any limit was hit
        $this->checkUserRateLimit($response);
        $this->checkClientRateLimit($response);
        $this->checkPostRateLimit($response);

        $body = $response->getBody();
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
        $userRemaining = (string) $response->getHeader('X-RateLimit-UserRemaining');
        $userLimit = (string) $response->getHeader('X-RateLimit-UserLimit');

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
        $clientRemaining = (string) $response->getHeader('X-RateLimit-ClientRemaining');
        $clientLimit = (string) $response->getHeader('X-RateLimit-ClientLimit');

        if ('' !== $clientRemaining && $clientRemaining < 1) {
            // X-RateLimit-UserReset: unix epoch
            $resetTime = date('Y-m-d H:i:s', (string) $response->getHeader('X-RateLimit-UserReset'));

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
        $postRemaining = (string) $response->getHeader('X-Post-Rate-Limit-Remaining');
        $postLimit = (string) $response->getHeader('X-Post-Rate-Limit-Limit');

        if ('' !== $postRemaining && $postRemaining < 1) {
            // X-Post-Rate-Limit-Reset: Time in seconds until your POST ratelimit is reset
            $resetTime = date('Y-m-d H:i:s', (string) $response->getHeader('X-Post-Rate-Limit-Reset'));

            throw new RateLimitException('No post credits available. The limit is ' . $postLimit . ' and will be reset at ' . $resetTime);
        }
    }
}
