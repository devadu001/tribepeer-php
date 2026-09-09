<?php

namespace TribePeer\Resources;

use TribePeer\Client;

class Communities
{
    public function __construct(private readonly Client $client) {}

    public function threads(string $communityUuid): array
    {
        return $this->client->partnerRequest('GET', '/communities/'.$communityUuid.'/threads');
    }
}
