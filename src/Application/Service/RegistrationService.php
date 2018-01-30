<?php

declare(strict_types=1);

namespace Damax\User\Application\Service;

use Damax\User\Application\Command\RegisterUser;
use Damax\User\Application\Dto\Assembler;
use Damax\User\Application\Dto\UserDto;
use Damax\User\Domain\Model\UserFactory;
use Damax\User\Domain\Model\UserRepository;

class RegistrationService
{
    private $userFactory;
    private $users;
    private $assembler;

    public function __construct(UserFactory $userFactory, UserRepository $users, Assembler $assembler)
    {
        $this->userFactory = $userFactory;
        $this->users = $users;
        $this->assembler = $assembler;
    }

    public function registerUser(RegisterUser $command): UserDto
    {
        $user = $this->userFactory->create($command);

        $this->users->save($user);

        return $this->assembler->toUserDto($user);
    }
}
