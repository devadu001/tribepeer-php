<?php

namespace TribePeer\Resources;

use TribePeer\Client;

class Campus
{
    public function __construct(private readonly Client $client) {}

    public function branding(?string $key = null, ?string $institution = null): array
    {
        $institution ??= $this->client->institutionUuid();
        $query = http_build_query(array_filter([
            'key' => $key,
            'institution' => $institution,
        ], fn ($v) => $v !== null && $v !== ''));
        $url = $this->client->productBase().'/branding'.($query ? '?'.$query : '');

        return $this->client->publicRequest('GET', $url);
    }

    public function join(string $joinCode): array
    {
        return $this->client->productRequest('POST', '/join', ['join_code' => $joinCode]);
    }

    public function tribesMine(): array
    {
        return $this->client->productRequest('GET', '/tribes/mine');
    }

    public function tribe(string $uuid): array
    {
        return $this->client->productRequest('GET', '/tribes/'.$uuid);
    }

    public function complete(string $tribeUuid, string $materialUuid): array
    {
        return $this->client->productRequest('POST', '/tribes/'.$tribeUuid.'/materials/'.$materialUuid.'/complete');
    }

    public function submitQuiz(string $tribeUuid, string $materialUuid, array $body): array
    {
        return $this->client->productRequest('POST', '/tribes/'.$tribeUuid.'/materials/'.$materialUuid.'/quiz', $body);
    }

    public function chatThreads(): array
    {
        return $this->client->productRequest('GET', '/chat/threads');
    }

    public function chatMessages(string $thread): array
    {
        return $this->client->productRequest('GET', '/chat/threads/'.$thread.'/messages');
    }

    public function chatSend(string $thread, string $body): array
    {
        return $this->client->productRequest('POST', '/chat/threads/'.$thread.'/messages', ['body' => $body]);
    }

    public function ask(string $message): array
    {
        return $this->client->productRequest('POST', '/ai/ask', ['message' => $message]);
    }

    public function manageTribes(): array
    {
        return $this->client->productRequest('GET', '/manage/tribes');
    }

    public function createTribe(array $body): array
    {
        return $this->client->productRequest('POST', '/manage/tribes', $body);
    }

    public function updateTribe(string $tribeUuid, array $body): array
    {
        return $this->client->productRequest('PATCH', '/manage/tribes/'.$tribeUuid, $body);
    }

    public function students(string $tribeUuid): array
    {
        return $this->client->productRequest('GET', '/manage/tribes/'.$tribeUuid.'/students');
    }

    public function joinCodes(): array
    {
        return $this->client->productRequest('GET', '/manage/join-codes');
    }

    public function createJoinCode(array $body): array
    {
        return $this->client->productRequest('POST', '/manage/join-codes', $body);
    }

    public function curriculum(string $tribeUuid): array
    {
        return $this->client->productRequest('GET', '/manage/tribes/'.$tribeUuid.'/curriculum');
    }

    public function createModule(string $tribeUuid, array $body): array
    {
        return $this->client->productRequest('POST', '/manage/tribes/'.$tribeUuid.'/modules', $body);
    }

    public function createMaterial(string $tribeUuid, array $body): array
    {
        return $this->client->productRequest('POST', '/manage/tribes/'.$tribeUuid.'/materials', $body);
    }

    public function updateMaterial(string $tribeUuid, string $materialUuid, array $body): array
    {
        return $this->client->productRequest('PATCH', '/manage/tribes/'.$tribeUuid.'/materials/'.$materialUuid, $body);
    }
}
