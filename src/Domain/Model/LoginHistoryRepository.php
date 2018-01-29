<?php

declare(strict_types=1);

namespace Damax\User\Domain\Model;

use Pagerfanta\Pagerfanta;
use Ramsey\Uuid\UuidInterface;

interface LoginHistoryRepository extends IdGenerator
{
    public function save(LoginHistory $login): void;

    public function paginateByUserId(UuidInterface $userId): Pagerfanta;

    public function lastByUserId(UuidInterface $userId): ?LoginHistory;
}
