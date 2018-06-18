<?php

declare(strict_types=1);

namespace Damax\User\Domain\Model;

interface LocaleRepository
{
    public function byCode(string $code): ?Locale;

    /**
     * @return Locale[]
     */
    public function all(): array;
}
