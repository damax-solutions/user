<?php

declare(strict_types=1);

namespace Damax\User\Doctrine\Orm;

use Damax\User\Domain\Model\LoginHistory;
use Damax\User\Domain\Model\LoginHistoryRepository as LoginHistoryRepositoryInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class LoginHistoryRepository extends EntityRepository implements LoginHistoryRepositoryInterface
{
    public function nextId(): UuidInterface
    {
        return Uuid::uuid4();
    }

    public function save(LoginHistory $login): void
    {
        $this->getEntityManager()->persist($login);
        $this->getEntityManager()->flush($login);
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
        return $this
            ->createQueryBuilder('l')
            ->where('IDENTITY(l.user) = :user_id')
            ->orderBy('l.createdAt', 'DESC')
            ->setParameter('user_id', $userId)
        ;
    }
}
