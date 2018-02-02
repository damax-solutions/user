<?php

declare(strict_types=1);

namespace Damax\User\Doctrine\Orm;

use Damax\User\Domain\Model\Permission;
use Damax\User\Domain\Model\PermissionRepository as PermissionRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class PermissionRepository implements PermissionRepositoryInterface
{
    use OrmRepositoryTrait;

    public function __construct(EntityManagerInterface $em, string $permissionClassName)
    {
        $this->em = $em;
        $this->className = $permissionClassName;
    }

    public function byCode(string $code): ?Permission
    {
        /** @var Permission $permission */
        $permission = $this->em->find($this->className, $code);

        return $permission;
    }

    /**
     * @return Permission[]
     */
    public function byCategory(string $category): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.category = :category')
            ->orderBy('p.code', 'ASC')
            ->setParameter('category', $category)
            ->getQuery()
            ->getResult()
        ;
    }

    public function save(Permission $permission): void
    {
        $this->em->persist($permission);
        $this->em->flush($permission);
    }

    public function remove(Permission $permission): void
    {
        $this->em->remove($permission);
        $this->em->flush($permission);
    }
}
