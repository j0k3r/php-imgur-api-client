<?php

namespace Imgur\Exception;

/**
 * RuntimeException
 *
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */
class RuntimeException extends \RuntimeException implements ExceptionInterface {
    public function __construct($error) {
        parent::__construct($error);
    }
}
