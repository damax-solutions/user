<?php

declare(strict_types=1);

namespace Damax\User\Doctrine\Orm;

use Damax\Common\Doctrine\Orm\OrmRepositoryTrait;
use Damax\User\Domain\Model\Email;
use Damax\User\Domain\Model\MobilePhone;
use Damax\User\Domain\Model\User;
use Damax\User\Domain\Model\UserRepository as UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class UserRepository implements UserRepositoryInterface
{
    use OrmRepositoryTrait;

    public function __construct(EntityManagerInterface $em, string $userClassName)
    {
        $this->em = $em;
        $this->className = $userClassName;
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
        /** @var User $user */
        $user = $this->em->find($this->className, $id);

        return $user;
    }

    public function byEmail(Email $email): ?User
    {
        return $this->createQueryBuilder('u')
            ->where('u.email.email = :email')
            ->setParameter('email', (string) $email)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function byMobilePhone(MobilePhone $mobilePhone): ?User
    {
        return $this->createQueryBuilder('u')
            ->where('u.mobilePhone.number = :number')
            ->setParameter('number', $mobilePhone->number())
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function paginate(): Pagerfanta
    {
        $qb = $this->createQueryBuilder('u')
            ->addSelect('r')
            ->leftJoin('u.roles', 'r')
            ->orderBy('u.createdAt', 'DESC')
        ;

        return new Pagerfanta(new DoctrineORMAdapter($qb, true, false));
    }

    public function size(): int
    {
        return (int) $this->createQueryBuilder('u')
            ->select('COUNT(u)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
