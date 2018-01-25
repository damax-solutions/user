<?php

declare(strict_types=1);

namespace Damax\User\Domain\Model;

final class Email
{
    private $email;
    private $verified = false;

    public static function fromString(string $email): self
    {
        return new self($email);
    }

    public function email(): string
    {
        return $this->email;
    }

    public function verified(): bool
    {
        return $this->verified;
    }

    public function verify(): self
    {
        $email = clone $this;
        $email->verified = true;

        return $email;
    }

    public function __toString(): string
    {
        return $this->email;
    }

    public function sameAs(self $email): bool
    {
        return $this->email === $email->email && $this->verified === $email->verified;
    }

    private function __construct(string $email)
    {
        $this->email = $email;
    }
}
