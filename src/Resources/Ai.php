<?php

namespace TribePeer\Resources;

use TribePeer\Client;

class Ai
{
    public function __construct(private readonly Client $client) {}

    public function chat(array $body): array
    {
        return $this->client->partnerRequest('POST', '/ai/chat', $body);
    }

    public function usage(): array
    {
        return $this->client->partnerRequest('GET', '/ai/usage');
    }
}
