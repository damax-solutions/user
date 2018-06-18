<?php

declare(strict_types=1);

namespace Damax\User\Domain\Model;

interface TimezoneRepository
{
    public function byId(string $code): ?Timezone;

    /**
     * @return Timezone[]
     */
    public function all(): array;
}
