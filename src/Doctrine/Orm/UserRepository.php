<?php

declare(strict_types=1);

namespace Damax\User\Doctrine\Orm;

use Damax\User\Domain\Model\Email;
use Damax\User\Domain\Model\MobilePhone;
use Damax\User\Domain\Model\User;
use Damax\User\Domain\Model\UserRepository as UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class UserRepository implements UserRepositoryInterface
{
    private $em;
    private $className;

    public function __construct(EntityManagerInterface $em, string $className = User::class)
    {
        $this->em = $em;
        $this->className = $className;
    }

    public function nextId(): UuidInterface
    {
        return Uuid::uuid4();
    }

    public function save(User $user): void
    {
        $this->em->persist($user);
        $this->em->flush($user);
    }

    public function byId(UuidInterface $id): ?User
    {
        return $this->em->find($this->className, $id);
    }

    public function byEmail(Email $email): ?User
    {
        return $this->createQueryBuilder()
            ->where('u.email.email = :email')
            ->setParameter('email', (string) $email)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function byMobilePhone(MobilePhone $mobilePhone): ?User
    {
        return $this->createQueryBuilder()
            ->where('u.mobilePhone.number = :number')
            ->setParameter('number', $mobilePhone->number())
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function paginate(): Pagerfanta
    {
        $qb = $this->createQueryBuilder()
            ->addSelect('r')
            ->leftJoin('u.roles', 'r')
            ->orderBy('u.createdAt', 'DESC')
        ;

        return new Pagerfanta(new DoctrineORMAdapter($qb, true, false));
    }

    public function size(): int
    {
        return (int) $this->createQueryBuilder()
            ->select('COUNT(u)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    private function createQueryBuilder(): QueryBuilder
    {
        return $this->em
            ->createQueryBuilder()
            ->select('u')
            ->from($this->className, 'u')
        ;
    }
}
