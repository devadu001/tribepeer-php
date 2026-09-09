<?php

namespace TribePeer\Resources;

use TribePeer\Client;

class Users
{
    public function __construct(private readonly Client $client) {}

    public function register(array $body): array
    {
        return $this->client->usersRegister($body);
    }

    public function login(array $body): array
    {
        return $this->client->usersLogin($body);
    }

    public function verify(array $body): array
    {
        return $this->client->usersVerify($body);
    }

    public function me(): array
    {
        return $this->client->usersMe();
    }
}
