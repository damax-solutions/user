<?php

declare(strict_types=1);

namespace Damax\User\Domain\TokenGenerator;

interface TokenGenerator
{
    public function generateToken(): string;
}
