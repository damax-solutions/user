<?php

declare(strict_types=1);

namespace Damax\User\Doctrine\Orm;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

trait OrmRepositoryTrait
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var string
     */
    private $className;

    private function createQueryBuilder(string $alias): QueryBuilder
    {
        return $this->em
            ->createQueryBuilder()
            ->select($alias)
            ->from($this->className, $alias)
        ;
    }
}
