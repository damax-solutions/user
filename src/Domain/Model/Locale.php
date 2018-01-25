<?php

declare(strict_types=1);

namespace Damax\User\Domain\Model;

use Symfony\Component\Intl\Intl;

final class Locale
{
    private $code;

    public static function fromCode(string $code): self
    {
        return new self($code);
    }

    public function code(): string
    {
        return $this->code;
    }

    public function name(): string
    {
        return Intl::getLocaleBundle()->getLocaleName($this->code) ?? $this->code;
    }

    public function __toString(): string
    {
        return $this->code;
    }

    private function __construct(string $code)
    {
        $this->code = strtolower($code);
    }
}
