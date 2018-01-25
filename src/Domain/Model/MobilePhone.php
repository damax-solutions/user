<?php

declare(strict_types=1);

namespace Damax\User\Domain\Model;

final class MobilePhone
{
    private $number;
    private $verified;

    public static function fromNumber(int $number): self
    {
        return new self($number);
    }

    public function number(): int
    {
        return $this->number;
    }

    public function verified(): bool
    {
        return $this->verified;
    }

    public function verify(): self
    {
        $mobilePhone = clone $this;
        $mobilePhone->verified = true;

        return $mobilePhone;
    }

    public function __toString(): string
    {
        return '+' . strval($this->number);
    }

    public function sameAs(self $mobilePhone): bool
    {
        return $this->number === $mobilePhone->number && $this->verified === $mobilePhone->verified;
    }

    private function __construct(int $number)
    {
        $this->number = $number;
    }
}
