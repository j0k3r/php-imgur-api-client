<?php

namespace Imgur\Listener;

use Guzzle\Common\Event;
use Imgur\Exception\ErrorException;
use Imgur\Exception\RateLimitException;
use Imgur\Exception\RuntimeException;

/**
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */
class ErrorListener
{
    /**
     * {@inheritdoc}
     */
    public function onRequestError(Event $event)
    {
        $request = $event['request'];
        $response = $request->getResponse();

        if (!$response->isClientError() && !$response->isServerError()) {
            return;
        }

        //check if any limit was hit
        $userRemaining = (string) $response->getHeader('X-RateLimit-UserRemaining');
        $userLimit = (string) $response->getHeader('X-RateLimit-UserLimit');

        if ('' !== $userRemaining && $userRemaining < 1) {
            throw new RateLimitException('No user credits available. The limit is ' . $userLimit);
        }

        $clientRemaining = (string) $response->getHeader('X-RateLimit-ClientRemaining');
        $clientLimit = (string) $response->getHeader('X-RateLimit-ClientLimit');

        if ('' !== $clientRemaining && $clientRemaining < 1) {
            // X-RateLimit-UserReset: unix epoch
            $resetTime = date('Y-m-d H:i:s', $response->getHeader('X-RateLimit-UserReset'));

            throw new RateLimitException('No application credits available. The limit is ' . $clientLimit . ' and will be reset at ' . $resetTime);
        }

        $body = $response->getBody(true);
        $responseData = json_decode($body, true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            $responseData = $body;
        }

        if (is_array($responseData) && isset($responseData['data']) && isset($responseData['data']['error'])) {
            throw new ErrorException('Request to: ' . $responseData['data']['request'] . ' failed with: "' . $responseData['data']['error'] . '"');
        }

        throw new RuntimeException(is_array($responseData) && isset($responseData['message']) ? $responseData['message'] : $responseData, $response->getStatusCode());
    }
}
