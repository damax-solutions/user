<?php

declare(strict_types=1);

namespace Damax\User\Domain\NameFormatter;

use Damax\User\Domain\Model\Name;

class StandardNameFormatter implements NameFormatter
{
    public function full(Name $name): ?string
    {
        return trim(trim($name->firstName() . ' ' . $name->middleName()) . ' ' . $name->lastName()) ?: null;
    }
}
