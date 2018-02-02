<?php

declare(strict_types=1);

namespace Damax\User\Domain\Model;

interface RoleRepository
{
    public function byCode(string $code): ?Role;

    /**
     * @return Role[]
     */
    public function all(): array;

    public function save(Role $role): void;

    public function remove(Role $role): void;
}
