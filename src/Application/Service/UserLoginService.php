<?php

declare(strict_types=1);

namespace Damax\User\Application\Service;

use Damax\User\Application\Command\RecordLogin;
use Damax\User\Application\Dto\Assembler;
use Damax\User\Application\Dto\UserLoginDto;
use Damax\User\Domain\Model\LoginHistory;
use Damax\User\Domain\Model\LoginHistoryRepository;
use Damax\User\Domain\Model\UserRepository;
use Damax\User\Pagerfanta\CallableDecoratorAdapter;
use Pagerfanta\Pagerfanta;
use Ramsey\Uuid\Uuid;

class UserLoginService
{
    use UserServiceTrait;

    private $users;
    private $logins;
    private $assembler;

    public function __construct(UserRepository $users, LoginHistoryRepository $logins, Assembler $assembler)
    {
        $this->users = $users;
        $this->logins = $logins;
        $this->assembler = $assembler;
    }

    public function recordLogin(RecordLogin $command): UserLoginDto
    {
        $login = new LoginHistory(
            $this->logins->nextId(),
            $this->getUser($command->userId),
            $command->userId,
            $command->clientIp,
            $command->serverIp,
            $command->userAgent
        );

        $this->logins->save($login);

        return $this->assembler->toUserLoginDto($login);
    }

    public function fetchRangeByUser(string $userId): Pagerfanta
    {
        $adapter = $this->logins
            ->paginateByUserId(Uuid::fromString($userId))
            ->getAdapter()
        ;

        return new Pagerfanta(new CallableDecoratorAdapter($adapter, [$this->assembler, 'toUserLoginDto']));
    }
}
