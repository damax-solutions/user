<?php

declare(strict_types=1);

namespace Damax\User\Domain\NameFormatter;

use Damax\User\Domain\Model\Name;

interface NameFormatter
{
    public function full(Name $name): ?string;
}
