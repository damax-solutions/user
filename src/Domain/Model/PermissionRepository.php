<?php

declare(strict_types=1);

namespace Damax\User\Domain\Model;

interface PermissionRepository
{
    public function byCode(string $code): ?Permission;

    /**
     * @return Permission[]
     */
    public function byCategory(string $category): array;

    public function save(Permission $permission): void;

    public function remove(Permission $permission): void;
}
