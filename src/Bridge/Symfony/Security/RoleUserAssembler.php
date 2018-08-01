<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Security;

use Damax\User\Domain\Model\User as UserModel;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;

class RoleUserAssembler implements UserAssembler
{
    private $usernameAccessor;

    public function __construct(string $usernameAccessor)
    {
        $this->usernameAccessor = $usernameAccessor;
    }

    public function assemble(UserModel $user): UserInterface
    {
        return new User(
            (string) $user->id(),
            (string) call_user_func([$user, $this->usernameAccessor]),
            array_map([$this, 'toRole'], $user->permissions()),
            $user->password()->password(),
            $user->password()->salt(),
            $user->password()->expired(),
            (string) $user->timezone(),
            (string) $user->locale(),
            $user->enabled()
        );
    }

    private function toRole(string $role): Role
    {
        return new Role('ROLE_' . strtoupper($role));
    }
}
