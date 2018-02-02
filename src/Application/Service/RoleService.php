<?php

declare(strict_types=1);

namespace Damax\User\Application\Service;

use Damax\User\Application\Command\CreateRole;
use Damax\User\Application\Command\UpdateRole;
use Damax\User\Application\Dto\Assembler;
use Damax\User\Application\Dto\RoleDto;
use Damax\User\Application\Exception\RoleAlreadyExists;
use Damax\User\Application\Exception\RoleNotFound;
use Damax\User\Domain\Model\RoleFactory;
use Damax\User\Domain\Model\RoleRepository;

class RoleService
{
    private $roles;
    private $factory;
    private $assembler;

    public function __construct(RoleRepository $roles, RoleFactory $factory, Assembler $assembler)
    {
        $this->roles = $roles;
        $this->factory = $factory;
        $this->assembler = $assembler;
    }

    /**
     * @throws RoleNotFound
     */
    public function fetch(string $code): RoleDto
    {
        if (null === $role = $this->roles->byCode($code)) {
            throw RoleNotFound::byCode($code);
        }

        return $this->assembler->toRoleDto($role);
    }

    /**
     * @return RoleDto[]
     */
    public function fetchAll(): array
    {
        return array_map([$this->assembler, 'toRoleDto'], $this->roles->all());
    }

    /**
     * @throws RoleAlreadyExists
     */
    public function create(CreateRole $command): RoleDto
    {
        if ($this->roles->byCode($command->role->code)) {
            throw RoleAlreadyExists::withCode($command->role->code);
        }

        $role = $this->factory->create($command->role);

        $this->roles->save($role);

        return $this->assembler->toRoleDto($role);
    }

    /**
     * @throws RoleNotFound
     */
    public function delete(string $code): RoleDto
    {
        if (null === $role = $this->roles->byCode($code)) {
            throw RoleNotFound::byCode($code);
        }

        $this->roles->remove($role);

        return $this->assembler->toRoleDto($role);
    }

    /**
     * @throws RoleNotFound
     */
    public function update(UpdateRole $command): RoleDto
    {
        if (null === $role = $this->roles->byCode($command->code)) {
            throw RoleNotFound::byCode($command->code);
        }

        $role->update($command->role->name, $command->role->permissions);

        $this->roles->save($role);

        return $this->assembler->toRoleDto($role);
    }
}
