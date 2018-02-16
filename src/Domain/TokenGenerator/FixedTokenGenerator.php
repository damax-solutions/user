<?php

declare(strict_types=1);

namespace Damax\User\Domain\TokenGenerator;

class FixedTokenGenerator implements TokenGenerator
{
    private $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function generateToken(): string
    {
        return $this->token;
    }
}
