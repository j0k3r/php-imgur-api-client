<?php

namespace Imgur\Exception;

/**
 *
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */
class RateLimitException extends RuntimeException {
    
    public function __construct($error) {
        parent::__construct($error);
    }
}
