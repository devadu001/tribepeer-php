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

    public function quiz(array $body): array
    {
        return $this->client->partnerRequest('POST', '/ai/quiz', $body);
    }

    public function material(array $body): array
    {
        return $this->client->partnerRequest('POST', '/ai/material', $body);
    }

    public function assignment(array $body): array
    {
        return $this->client->partnerRequest('POST', '/ai/assignment', $body);
    }

    public function documents(array $body): array
    {
        return $this->client->partnerRequest('POST', '/ai/documents', $body);
    }

    public function curriculumFromPdf(array $body): array
    {
        return $this->client->partnerRequest('POST', '/ai/curriculum-from-pdf', $body);
    }

    public function usage(): array
    {
        return $this->client->partnerRequest('GET', '/ai/usage');
    }
}
