<?php

declare(strict_types=1);

namespace Damax\User\Domain\TokenGenerator;

class RandomTokenGenerator implements TokenGenerator
{
    private $size;

    public function __construct(int $size = 20)
    {
        $this->size = $size;
    }

    public function generateToken(): string
    {
        return sha1(random_bytes($this->size));
    }
}
