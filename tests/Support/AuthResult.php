<?php

namespace Tests\Support;

use App\Models\User;

class AuthResult
{
    public function __construct(
        public readonly User $user,
        public readonly string $token
    ) {}

    public function asHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ];
    }
}
