<?php

declare(strict_types=1);

namespace Damax\User\Application\Service;

use Damax\User\Application\Command\ChangePassword;
use Damax\User\Domain\Model\UserRepository;
use Damax\User\Domain\Password\Encoder;

class PasswordService
{
    use UserServiceTrait;

    private $users;
    private $encoder;

    public function __construct(UserRepository $users, Encoder $encoder)
    {
        $this->users = $users;
        $this->encoder = $encoder;
    }

    public function changePassword(ChangePassword $command): void
    {
        $user = $this->getUser($command->userId);

        $user->changePassword($this->encoder->encode($command->newPassword));

        $this->users->save($user);
    }
}
