<?php

declare(strict_types=1);

namespace Damax\User\Doctrine\Orm;

use Damax\Common\Doctrine\Orm\OrmRepositoryTrait;
use Damax\User\Domain\Model\Role;
use Damax\User\Domain\Model\RoleRepository as RoleRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class RoleRepository implements RoleRepositoryInterface
{
    use OrmRepositoryTrait;

    public function __construct(EntityManagerInterface $em, string $roleClassName)
    {
        $this->em = $em;
        $this->className = $roleClassName;
    }

    public function byCode(string $code): ?Role
    {
        /** @var Role $role */
        $role = $this->em->find($this->className, $code);

        return $role;
    }

    /**
     * @return Role[]
     */
    public function all(): array
    {
        return $this->createQueryBuilder('r')
            ->orderBy('r.code', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function save(Role $role): void
    {
        $this->em->persist($role);
        $this->em->flush($role);
    }

    public function remove(Role $role): void
    {
        $this->em->remove($role);
        $this->em->flush($role);
    }
}
