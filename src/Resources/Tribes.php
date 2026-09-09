<?php

namespace TribePeer\Resources;

use TribePeer\Client;

class Tribes
{
    public function __construct(private readonly Client $client) {}

    public function list(): array
    {
        return $this->client->partnerRequest('GET', '/tribes');
    }

    public function create(array $body): array
    {
        return $this->client->partnerRequest('POST', '/tribes', $body);
    }

    public function get(string $uuid): array
    {
        return $this->client->partnerRequest('GET', '/tribes/'.$uuid);
    }
}
