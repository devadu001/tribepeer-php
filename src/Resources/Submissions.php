<?php

namespace TribePeer\Resources;

use TribePeer\Client;

class Submissions
{
    public function __construct(private readonly Client $client) {}

    public function grade(string $id, array $body): array
    {
        return $this->client->partnerRequest('POST', '/submissions/'.$id.'/grade', $body);
    }
}
