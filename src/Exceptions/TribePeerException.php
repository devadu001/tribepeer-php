<?php

namespace TribePeer\Exceptions;

use RuntimeException;

class TribePeerException extends RuntimeException
{
    public function __construct(
        string $message,
        public readonly int $status = 0,
        public readonly mixed $body = null,
    ) {
        parent::__construct($message, $status);
    }
}
