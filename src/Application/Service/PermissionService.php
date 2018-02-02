<?php

declare(strict_types=1);

namespace Damax\User\Application\Service;

use Damax\User\Application\Command\CreatePermission;
use Damax\User\Application\Dto\Assembler;
use Damax\User\Application\Dto\PermissionDto;
use Damax\User\Application\Exception\PermissionAlreadyExists;
use Damax\User\Application\Exception\PermissionNotFound;
use Damax\User\Domain\Model\PermissionFactory;
use Damax\User\Domain\Model\PermissionRepository;

class PermissionService
{
    private $permissions;
    private $factory;
    private $assembler;

    public function __construct(PermissionRepository $permissions, PermissionFactory $factory, Assembler $assembler)
    {
        $this->permissions = $permissions;
        $this->factory = $factory;
        $this->assembler = $assembler;
    }

    /**
     * @throws PermissionAlreadyExists
     */
    public function create(CreatePermission $command): PermissionDto
    {
        if ($this->permissions->byCode($command->permission->code)) {
            throw PermissionAlreadyExists::withCode($command->permission->code);
        }

        $permission = $this->factory->create($command->permission);

        $this->permissions->save($permission);

        return $this->assembler->toPermissionDto($permission);
    }

    /**
     * @throws PermissionNotFound
     */
    public function delete(string $code): PermissionDto
    {
        if (null === $permission = $this->permissions->byCode($code)) {
            throw PermissionNotFound::byCode($code);
        }

        $this->permissions->remove($permission);

        return $this->assembler->toPermissionDto($permission);
    }

    /**
     * @return PermissionDto[]
     */
    public function fetchByCategory(string $category): array
    {
        return array_map([$this->assembler, 'toPermissionDto'], $this->permissions->byCategory($category));
    }
}
