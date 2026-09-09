<?php

namespace TribePeer\Resources;

use TribePeer\Client;

class Materials
{
    public function __construct(private readonly Client $client) {}

    public function list(string $tribeUuid): array
    {
        return $this->client->partnerRequest('GET', '/tribes/'.$tribeUuid.'/materials');
    }
}
