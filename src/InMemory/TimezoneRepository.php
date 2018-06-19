<?php

declare(strict_types=1);

namespace Damax\User\InMemory;

use Damax\User\Domain\Model\Timezone;
use Damax\User\Domain\Model\TimezoneRepository as TimezoneRepositoryInterface;

class TimezoneRepository implements TimezoneRepositoryInterface
{
    private $ids;

    public function __construct(array $timezoneIds)
    {
        $this->ids = $timezoneIds;
    }

    public function byId(string $id): ?Timezone
    {
        return in_array($id, $this->ids) ? Timezone::fromId($id) : null;
    }

    public function all(): array
    {
        return array_map([Timezone::class, 'fromId'], $this->ids);
    }
}
