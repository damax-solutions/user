<?php

declare(strict_types=1);

namespace Damax\User\Domain\Model;

interface RoleFactory
{
    public function create($data): Role;
}
