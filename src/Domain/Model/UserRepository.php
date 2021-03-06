<?php

declare(strict_types=1);

namespace Damax\User\Domain\Model;

use Pagerfanta\Pagerfanta;
use Ramsey\Uuid\UuidInterface;

interface UserRepository
{
    public function nextId(): UuidInterface;

    public function byId(UuidInterface $id): ?User;

    public function byEmail(Email $email): ?User;

    public function byMobilePhone(MobilePhone $mobilePhone): ?User;

    public function paginate(): Pagerfanta;

    public function save(User $user): void;

    public function size(): int;
}
