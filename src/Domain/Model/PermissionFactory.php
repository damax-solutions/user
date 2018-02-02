<?php

declare(strict_types=1);

namespace Damax\User\Domain\Model;

interface PermissionFactory
{
    public function create($data): Permission;
}
