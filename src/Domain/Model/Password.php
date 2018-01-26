<?php

declare(strict_types=1);

namespace Damax\User\Domain\Model;

use DateTimeImmutable;

final class Password
{
    private $password;
    private $salt;
    private $expiresAt;

    public static function valid3Months(string $password, string $salt): self
    {
        return new self($password, $salt, '3 months');
    }

    public static function valid6Months(string $password, string $salt): self
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
        return self::now() >= $this->expiresAt;
    }

    public function invalidate(): self
    {
        $password = clone $this;
        $password->expiresAt = self::now();

        return $password;
    }

    private function __construct(string $password, string $salt, string $period)
    {
        $this->password = $password;
        $this->salt = $salt;
        $this->expiresAt = self::now()->modify($period);
    }

    private static function now(): DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', time()));
    }
}
