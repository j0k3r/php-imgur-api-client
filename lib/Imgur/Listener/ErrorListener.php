<?php

namespace Imgur\Listener;

use Guzzle\Common\Event;
use Guzzle\Http\Message\Response;

use Imgur\Exception\ApiLimitExceedException;

/**
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */
class ErrorListener {
    /**
     * 
     * @var array
     */
    private $options;

    /**
     * 
     * @param array $options
     */
    public function __construct($options) {
        $this->options = $options;
    }

    /**
     * {@inheritDoc}
     */
    public function onRequestError(Event $event)
    {
        $request = $event['request'];
        $response = $request->getResponse();

        if ($response->isClientError() || $response->isServerError()) {
            //check if any limit was hit
            $userAvailableCallsCount = $response->getHeader('X-RateLimit-UserRemaining');
            $userTotalCallsAvailable = $response->getHeader('X-RateLimit-UserLimit');
            
            $applicationAvailableCallsCount = $response->getHeader('X-RateLimit-ClientLimit');
            $applicationTotalCallsAvailable = $response->getHeader('X-RateLimit-ClientRemaining');
            
            if(!empty($userAvailableCallsCount) && $userAvailableCallsCount < 1) {
                throw new \Imgur\Exception\RateLimitException('No user credits available. The limit is '.$userTotalCallsAvailable);
            }
            
            if(!empty($applicationAvailableCallsCount) && $applicationTotalCallsAvailable < 1) {
                $applicationTotalCallsResetTime = $response->getHeader('X-RateLimit-UserReset'); // unix epoch
                $applicationTotalCallsResetTime = date('Y-m-d H:i:s', $applicationTotalCallsResetTime);
                throw new \Imgur\Exception\RateLimitException('No application credits available. The limit is '.$applicationTotalCallsAvailable.' '
                        . 'and will be reset at '.$applicationTotalCallsResetTime);
            }
            
            $responseData = $response->json();
            
            if(!empty($responseData['data']) && !empty($responseData['error'])) {
                throw new \Imgur\Exception\RateLimitException('Request to: '.$responseData['data']['request'].' failed with: "'.$responseData['data']['error'].'"');
            }
        }
    }
}
