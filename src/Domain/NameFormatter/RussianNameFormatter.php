<?php

declare(strict_types=1);

namespace Damax\User\Domain\NameFormatter;

use Damax\User\Domain\Model\Name;

class RussianNameFormatter implements NameFormatter
{
    public function full(Name $name): ?string
    {
        return trim($name->lastName() . ' ' . $name->firstName() . ' ' . $name->middleName()) ?: null;
    }
}
