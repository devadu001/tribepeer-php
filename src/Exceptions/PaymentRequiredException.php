<?php

namespace TribePeer\Exceptions;

class PaymentRequiredException extends TribePeerException
{
    /** @var array<string, mixed>|null */
    public readonly ?array $upgrade;

    public function __construct(string $message, mixed $body = null)
    {
        parent::__construct($message, 402, $body);
        $this->upgrade = is_array($body) && isset($body['upgrade']) && is_array($body['upgrade'])
            ? $body['upgrade']
            : null;
    }
}
