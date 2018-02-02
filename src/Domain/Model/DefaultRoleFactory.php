<?php

declare(strict_types=1);

namespace Damax\User\Domain\Model;

use Assert\Assert;

class DefaultRoleFactory implements RoleFactory
{
    public function create($data): Role
    {
        Assert::that($data)
            ->keyIsset('code')
            ->keyIsset('name')
            ->keyIsset('permissions')
        ;

        return new Role($data['code'], $data['name'], $data['permissions']);
    }
}
