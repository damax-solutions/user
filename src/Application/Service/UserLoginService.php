<?php

declare(strict_types=1);

namespace Damax\User\Application\Service;

use Damax\User\Application\Dto\Assembler;
use Damax\User\Domain\Model\LoginHistoryRepository;
use Damax\User\Pagerfanta\CallableDecoratorAdapter;
use Pagerfanta\Pagerfanta;
use Ramsey\Uuid\Uuid;

class UserLoginService
{
    private $logins;
    private $assembler;

    public function __construct(LoginHistoryRepository $logins, Assembler $assembler)
    {
        $this->logins = $logins;
        $this->assembler = $assembler;
    }

    public function fetchRange(string $userId): Pagerfanta
    {
        $adapter = $this->logins
            ->paginateByUserId(Uuid::fromString($userId))
            ->getAdapter()
        ;

        return new Pagerfanta(new CallableDecoratorAdapter($adapter, [$this->assembler, 'toUserLoginDto']));
    }
}
