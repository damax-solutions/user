<?php

declare(strict_types=1);

namespace Damax\User\Domain\Model;

use Assert\Assert;

class DefaultPermissionFactory implements PermissionFactory
{
    public function create($data): Permission
    {
        Assert::that($data)
            ->keyIsset('code')
            ->keyIsset('category')
        ;

        return new Permission($data['code'], $data['category'], $data['description'] ?? null);
    }
}
