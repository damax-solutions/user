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
    use UserServiceTrait;

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
        $creator = $command->creatorId ? $this->getUser($command->creatorId) : null;

        $user = $this->userFactory->create($command, $creator);

        $this->users->save($user);

        return $this->assembler->toUserDto($user);
    }
}
