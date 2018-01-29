<?php

declare(strict_types=1);

namespace Damax\User\Doctrine\Orm;

use Damax\User\Domain\Model\Email;
use Damax\User\Domain\Model\MobilePhone;
use Damax\User\Domain\Model\User;
use Damax\User\Domain\Model\UserRepository as UserRepositoryInterface;
use Doctrine\ORM\EntityRepository;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class UserRepository extends EntityRepository implements UserRepositoryInterface
{
    public function nextId(): UuidInterface
    {
        return Uuid::uuid4();
    }

    public function save(User $user): void
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush($user);
    }

    public function byId(UuidInterface $id): ?User
    {
        /** @var User $user */
        $user = $this->find($id);

        return $user;
    }

    public function byEmail(Email $email): ?User
    {
        /** @var User $user */
        $user = $this->findOneBy(['email.email' => (string) $email]);

        return $user;
    }

    public function byMobilePhone(MobilePhone $mobilePhone): ?User
    {
        /** @var User $user */
        $user = $this->findOneBy(['mobilePhone.number' => $mobilePhone->number()]);

        return $user;
    }

    public function paginate(): Pagerfanta
    {
        $qb = $this
            ->createQueryBuilder('u')
            ->addSelect('r')
            ->leftJoin('u.roles', 'r')
            ->orderBy('u.createdAt', 'DESC')
        ;

        return new Pagerfanta(new DoctrineORMAdapter($qb, true, false));
    }

    public function size(): int
    {
        return $this->count([]);
    }
}
