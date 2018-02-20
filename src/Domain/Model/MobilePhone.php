<?php

declare(strict_types=1);

namespace Damax\User\Domain\Model;

final class MobilePhone
{
    private $number;
    private $confirmed = false;

    public static function fromString($number): self
    {
        return new self((int) ltrim((string) $number, '+'));
    }

    public static function fromNumber(int $number): self
    {
        return new self($number);
    }

    public function number(): int
    {
        return $this->number;
    }

    public function confirmed(): bool
    {
        return $this->confirmed;
    }

    public function confirm(): self
    {
        $mobilePhone = clone $this;
        $mobilePhone->confirmed = true;

        return $mobilePhone;
    }

    public function __toString(): string
    {
        return '+' . strval($this->number);
    }

    public function sameAs(self $mobilePhone): bool
    {
        return $this->number === $mobilePhone->number && $this->confirmed === $mobilePhone->confirmed;
    }

    private function __construct(int $number)
    {
        $this->number = $number;
    }
}
