<?php

declare(strict_types=1);

namespace Damax\User\Doctrine\Orm;

use Damax\User\Domain\Model\ActionRequest;
use Damax\User\Domain\Model\ActionRequestRepository as ActionRequestRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;

class ActionRequestRepository implements ActionRequestRepositoryInterface
{
    use OrmRepositoryTrait;

    public function __construct(EntityManagerInterface $em, string $actionRequestClassName)
    {
        $this->em = $em;
        $this->className = $actionRequestClassName;
    }

    public function byToken(string $token): ?ActionRequest
    {
        /** @var ActionRequest $request */
        $request = $this->em->find($this->className, $token);

        return $request;
    }

    public function byUserId(UuidInterface $userId): array
    {
        return $this
            ->createQueryBuilder('r')
            ->where('IDENTITY(r.user) = :user_id')
            ->setParameter('user_id', $userId)
            ->getQuery()
            ->getResult()
        ;
    }

    public function save(ActionRequest $request): void
    {
        $this->em->persist($request);
        $this->em->flush($request);
    }

    public function remove(ActionRequest $request): void
    {
        $this->em->remove($request);
        $this->em->flush($request);
    }
}
