<?php

namespace TribePeer;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\GuzzleException;
use TribePeer\Exceptions\PaymentRequiredException;
use TribePeer\Exceptions\TribePeerException;
use TribePeer\Resources\Ai;
use TribePeer\Resources\Campus;
use TribePeer\Resources\Communities;
use TribePeer\Resources\Materials;
use TribePeer\Resources\Submissions;
use TribePeer\Resources\Tribes;
use TribePeer\Resources\Users;

class Client
{
    private Guzzle $http;

    private ?array $partnerToken = null;

    private ?array $userToken = null;

    public function __construct(
        private readonly string $clientId,
        private readonly string $clientSecret,
        private readonly string $partnerBase = 'https://tribepeer.com/api/partner/v1',
        private readonly string $productBase = 'https://tribepeer.com/api/product/v1',
        private readonly ?string $institutionUuid = null,
        ?Guzzle $http = null,
    ) {
        $this->http = $http ?? new Guzzle([
            'http_errors' => false,
            'timeout' => 30,
        ]);
    }

    public function productBase(): string
    {
        return rtrim($this->productBase, '/');
    }

    public function institutionUuid(): ?string
    {
        return $this->institutionUuid;
    }

    /**
     * @return array<string, mixed>
     */
    public function token(): array
    {
        $this->partnerToken = $this->request('POST', $this->partnerBase.'/auth/token', [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
        ]);

        return $this->partnerToken;
    }

    /**
     * @param  array<string, mixed>  $body
     * @return array<string, mixed>
     */
    public function usersLogin(array $body): array
    {
        $this->userToken = $this->request('POST', $this->productBase.'/auth/login', $body);

        return $this->userToken;
    }

    /**
     * @param  array<string, mixed>  $body
     * @return array<string, mixed>
     */
    public function usersRegister(array $body): array
    {
        return $this->request('POST', $this->productBase.'/auth/register', $body);
    }

    /**
     * @param  array<string, mixed>  $body
     * @return array<string, mixed>
     */
    public function usersVerify(array $body): array
    {
        $this->userToken = $this->request('POST', $this->productBase.'/auth/verify', $body);

        return $this->userToken;
    }

    /**
     * @return array<string, mixed>
     */
    public function usersMe(): array
    {
        return $this->request('GET', $this->productBase.'/me', null, $this->userAccessToken());
    }

    /**
     * @return array<string, mixed>
     */
    public function me(): array
    {
        return $this->partner('GET', '/me');
    }

    /**
     * @param  array<string, mixed>  $body
     * @return array<string, mixed>
     */
    public function updateBranding(array $body): array
    {
        return $this->partner('PATCH', '/me/branding', $body);
    }

    public function tribes(): Tribes
    {
        return new Tribes($this);
    }

    public function materials(): Materials
    {
        return new Materials($this);
    }

    public function ai(): Ai
    {
        return new Ai($this);
    }

    public function users(): Users
    {
        return new Users($this);
    }

    public function campus(): Campus
    {
        return new Campus($this);
    }

    public function submissions(): Submissions
    {
        return new Submissions($this);
    }

    public function communities(): Communities
    {
        return new Communities($this);
    }

    /**
     * @param  array<string, mixed>|null  $body
     * @return array<string, mixed>
     */
    public function partnerRequest(string $method, string $path, ?array $body = null): array
    {
        return $this->partner($method, $path, $body);
    }

    /**
     * @param  array<string, mixed>|null  $body
     * @return array<string, mixed>
     */
    public function productRequest(string $method, string $path, ?array $body = null): array
    {
        return $this->request($method, $this->productBase().$path, $body, $this->userAccessToken());
    }

    /**
     * @param  array<string, mixed>|null  $body
     * @return array<string, mixed>
     */
    public function publicRequest(string $method, string $url, ?array $body = null): array
    {
        return $this->request($method, $url, $body);
    }

    /**
     * @param  array<string, mixed>|null  $body
     * @return array<string, mixed>
     */
    private function partner(string $method, string $path, ?array $body = null): array
    {
        if ($this->partnerToken === null) {
            $this->token();
        }

        return $this->request($method, rtrim($this->partnerBase, '/').$path, $body, $this->partnerAccessToken());
    }

    private function partnerAccessToken(): ?string
    {
        return is_array($this->partnerToken) ? ($this->partnerToken['access_token'] ?? null) : null;
    }

    private function userAccessToken(): ?string
    {
        return is_array($this->userToken) ? ($this->userToken['access_token'] ?? null) : null;
    }

    /**
     * @param  array<string, mixed>|null  $body
     * @return array<string, mixed>
     */
    private function request(string $method, string $url, ?array $body = null, ?string $token = null): array
    {
        $headers = ['Accept' => 'application/json'];
        if ($token) {
            $headers['Authorization'] = 'Bearer '.$token;
        }
        if ($this->institutionUuid) {
            $headers['X-Institution-Uuid'] = $this->institutionUuid;
        }

        $options = ['headers' => $headers];
        if ($body !== null) {
            $options['json'] = $body;
        }

        try {
            $response = $this->http->request($method, $url, $options);
        } catch (GuzzleException $e) {
            throw new TribePeerException('TribePeer request failed: '.$e->getMessage(), 0, null);
        }

        $status = $response->getStatusCode();
        $decoded = json_decode((string) $response->getBody(), true);
        $data = is_array($decoded) ? $decoded : ['message' => (string) $response->getBody()];

        if ($status >= 400) {
            $message = (string) ($data['message'] ?? $data['error'] ?? 'TribePeer request failed ('.$status.')');
            if ($status === 402) {
                throw new PaymentRequiredException($message, $data);
            }
            throw new TribePeerException($message, $status, $data);
        }

        return $data;
    }
}
