<?php

namespace Imgur\Listener;

use Imgur\Exception\RateLimitException;
use Imgur\Exception\RuntimeException;
use Imgur\Exception\ErrorException;
use Guzzle\Common\Event;

/**
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */
class ErrorListener
{
    /**
     * @var array
     */
    private $options;

    /**
     * @param array $options
     */
    public function __construct($options)
    {
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function onRequestError(Event $event)
    {
        $request = $event['request'];
        $response = $request->getResponse();

        if ($response->isClientError() || $response->isServerError()) {
            //check if any limit was hit
            $userRemaining = (string) $response->getHeader('X-RateLimit-UserRemaining');
            $userLimit = (string) $response->getHeader('X-RateLimit-UserLimit');

            if (null !== $userRemaining && $userRemaining < 1) {
                throw new RateLimitException('No user credits available. The limit is '.$userLimit);
            }

            $clientLimit = (string) $response->getHeader('X-RateLimit-ClientLimit');
            $clientRemaining = (string) $response->getHeader('X-RateLimit-ClientRemaining');

            if (null !== $clientRemaining && $clientRemaining < 1) {
                // X-RateLimit-UserReset: unix epoch
                $resetTime = date('Y-m-d H:i:s', $response->getHeader('X-RateLimit-UserReset'));

                throw new RateLimitException('No application credits available. The limit is '.$clientLimit.' and will be reset at '.$resetTime);
            }

            $body = $response->getBody(true);
            $responseData = json_decode($body, true);
            if (JSON_ERROR_NONE !== json_last_error()) {
                $responseData = $body;
            }

            if (is_array($responseData) && isset($responseData['data']) && isset($responseData['error'])) {
                throw new ErrorException('Request to: '.$responseData['data']['request'].' failed with: "'.$responseData['data']['error'].'"');
            }

            throw new RuntimeException(is_array($responseData) && isset($responseData['message']) ? $responseData['message'] : $responseData, $response->getStatusCode());
        }
    }
}
