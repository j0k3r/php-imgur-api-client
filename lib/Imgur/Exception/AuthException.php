<?php

namespace Imgur\Exception;

/**
 * AuthException
 *
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */
class AuthException extends RuntimeException {
    public function __construct($message, $code = '') {
        parent::__construct($message, $code);
    }
}
