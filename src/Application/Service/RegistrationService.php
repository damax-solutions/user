<?php

declare(strict_types=1);

namespace Damax\User\Application\Service;

use Damax\User\Application\Command\RegisterUser;
use Damax\User\Application\Dto\Assembler;
use Damax\User\Application\Dto\UserDto;
use Damax\User\Application\Exception\UserAlreadyExists;
use Damax\User\Domain\Model\Email;
use Damax\User\Domain\Model\MobilePhone;
use Damax\User\Domain\Model\UserFactory;
use Damax\User\Domain\Model\UserRepository;

class RegistrationService
{
    use UserServiceTrait;

    private $userFactory;
    private $assembler;

    public function __construct(UserRepository $users, UserFactory $userFactory, Assembler $assembler)
    {
        $this->users = $users;
        $this->userFactory = $userFactory;
        $this->assembler = $assembler;
    }

    /**
     * @throws UserAlreadyExists
     */
    public function registerUser(RegisterUser $command): UserDto
    {
        if ($this->users->byEmail(Email::fromString($command->user->email))) {
            throw UserAlreadyExists::withEmail($command->user->email);
        }

        if ($this->users->byMobilePhone(MobilePhone::fromString($command->user->mobilePhone))) {
            throw UserAlreadyExists::withMobilePhone($command->user->mobilePhone);
        }

        $creator = $command->creatorId ? $this->getUser($command->creatorId) : null;

        $user = $this->userFactory->create($command->user, $creator);

        $this->users->save($user);

        return $this->assembler->toUserDto($user);
    }
}
