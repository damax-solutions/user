<?php

declare(strict_types=1);

namespace Damax\User\Domain\NameFormatter;

use Damax\User\Domain\Model\Name;

class JamesBondNameFormatter implements NameFormatter
{
    public function full(Name $name): ?string
    {
        return trim($name->lastName() . trim(', ' . $name->firstName()) . ' ' . $name->lastName(), ' ,') ?: null;
    }
}
