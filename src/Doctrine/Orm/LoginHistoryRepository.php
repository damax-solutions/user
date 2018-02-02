<?php

declare(strict_types=1);

namespace Damax\User\Doctrine\Orm;

use Damax\User\Domain\Model\LoginHistory;
use Damax\User\Domain\Model\LoginHistoryRepository as LoginHistoryRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class LoginHistoryRepository implements LoginHistoryRepositoryInterface
{
    use OrmRepositoryTrait;

    public function __construct(EntityManagerInterface $em, string $loginHistoryClassName)
    {
        $this->em = $em;
        $this->className = $loginHistoryClassName;
    }

    public function nextId(): UuidInterface
    {
        return Uuid::uuid4();
    }

    public function save(LoginHistory $login): void
    {
        $this->em->persist($login);
        $this->em->flush($login);
    }

    public function paginateByUserId(UuidInterface $userId): Pagerfanta
    {
        $qb = $this->createQueryBuilderByUserId($userId);

        return new Pagerfanta(new DoctrineORMAdapter($qb, true, false));
    }

    public function lastByUserId(UuidInterface $userId): ?LoginHistory
    {
        return $this
            ->createQueryBuilderByUserId($userId)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    private function createQueryBuilderByUserId(UuidInterface $userId): QueryBuilder
    {
        return $this->createQueryBuilder('l')
            ->where('IDENTITY(l.user) = :user_id')
            ->orderBy('l.createdAt', 'DESC')
            ->setParameter('user_id', $userId)
        ;
    }
}
