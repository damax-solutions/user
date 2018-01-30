<?php

declare(strict_types=1);

namespace Damax\User\Tests\Bridge\Symfony\Security;

use Damax\User\Bridge\Symfony\Security\User;

class UserFactory
{
    public function create(string $id = 'abc', array $roles = []): User
    {
        return new User($id, '123', $roles, 'qwerty', 'XYZ', false, 'Europe/Riga', 'ru', true);
    }
}
