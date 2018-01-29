<?php

declare(strict_types=1);

namespace Damax\User\Application\Service;

use Damax\User\Application\Dto\Assembler;
use Damax\User\Application\Dto\UserDto;
use Damax\User\Domain\Model\UserRepository;
use Damax\User\Pagerfanta\CallableDecoratorAdapter;
use Pagerfanta\Pagerfanta;

class UserService
{
    use UserServiceTrait;

    private $users;
    private $assembler;

    public function __construct(UserRepository $users, Assembler $assembler)
    {
        $this->users = $users;
        $this->assembler = $assembler;
    }

    public function fetch(string $userId): UserDto
    {
        $user = $this->getUser($userId);

        return $this->assembler->toUserDto($user);
    }

    public function fetchRange(): Pagerfanta
    {
        $adapter = $this->users->paginate()->getAdapter();

        return new Pagerfanta(new CallableDecoratorAdapter($adapter, [$this->assembler, 'toUserDto']));
    }

    public function enable(string $userId): UserDto
    {
        $user = $this->getUser($userId);
        $user->enable();

        $this->users->save($user);

        return $this->assembler->toUserDto($user);
    }

    public function disable(string $userId): UserDto
    {
        $user = $this->getUser($userId);
        $user->disable();

        $this->users->save($user);

        return $this->assembler->toUserDto($user);
    }
}
