<?php

namespace Imgur\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ErrorMiddleware
{
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
            ->then(function (ResponseInterface $response) use ($request, $options) {
                return $this->checkError($request, $options, $response);
            });
    }

    public function checkError(RequestInterface $request, array $options, ResponseInterface $response)
    {
        $code = $response->getStatusCode();
        if ($code < 400) {
            return $response;
        }

        throw RequestException::create($request, $response);
    }
}
