<?php

declare(strict_types=1);

namespace Damax\User\Domain\Model;

final class Email
{
    private $email;
    private $confirmed = false;

    public static function fromString(string $email): self
    {
        return new self($email);
    }

    public function email(): string
    {
        return $this->email;
    }

    public function confirmed(): bool
    {
        return $this->confirmed;
    }

    public function confirm(): self
    {
        $email = clone $this;
        $email->confirmed = true;

        return $email;
    }

    public function __toString(): string
    {
        return $this->email;
    }

    public function sameAs(self $email): bool
    {
        return $this->email === $email->email && $this->confirmed === $email->confirmed;
    }

    private function __construct(string $email)
    {
        $this->email = strtolower($email);
    }
}
