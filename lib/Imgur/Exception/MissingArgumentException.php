<?php

namespace Imgur\Exception;

class MissingArgumentException extends ErrorException
{
    /**
     * @param array|string $required
     */
    public function __construct($required, int $code = 0)
    {
        if (\is_string($required)) {
            $required = [$required];
        }

        parent::__construct(sprintf('One or more of required ("%s") parameters is missing!', implode('", "', $required)), $code);
    }
}
