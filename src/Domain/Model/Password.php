<?php

declare(strict_types=1);

namespace Damax\User\Domain\Model;

use DateTimeImmutable;

final class Password
{
    private $password;
    private $salt;
    private $expiresAt;

    public static function for3Months(string $password, string $salt): self
    {
        return new self($password, $salt, '3 months');
    }

    public static function for6Months(string $password, string $salt): self
    {
        return new self($password, $salt, '6 months');
    }

    public function password(): string
    {
        return $this->password;
    }

    public function salt(): string
    {
        return $this->salt;
    }

    public function expired(): bool
    {
        return new DateTimeImmutable() >= $this->expiresAt;
    }

    public function invalidate(): self
    {
        $password = clone $this;
        $password->expiresAt = new DateTimeImmutable();

        return $password;
    }

    private function __construct(string $password, string $salt, string $period)
    {
        $this->password = $password;
        $this->salt = $salt;
        $this->expiresAt = (new DateTimeImmutable())->modify($period);
    }
}
