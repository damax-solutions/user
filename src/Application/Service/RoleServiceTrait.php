<?php

declare(strict_types=1);

namespace Damax\User\Application\Service;

use Damax\User\Application\Exception\RoleNotFound;
use Damax\User\Domain\Model\Role;
use Damax\User\Domain\Model\RoleRepository;

trait RoleServiceTrait
{
    /**
     * @var RoleRepository
     */
    private $roles;

    /**
     * @throws RoleNotFound
     */
    private function getRole(string $code): Role
    {
        if (null === $role = $this->roles->byCode($code)) {
            throw RoleNotFound::byCode($code);
        }

        return $role;
    }
}
