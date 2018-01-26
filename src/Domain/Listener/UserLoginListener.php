<?php

declare(strict_types=1);

namespace Damax\User\Domain\Listener;

use Damax\User\Domain\Event\LoginRecorded;
use Damax\User\Domain\Model\UserRepository;

class UserLoginListener
{
    private $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function onLoginRecorded(LoginRecorded $event)
    {
        if (null === $user = $this->users->byId($event->userId())) {
            return;
        }

        $user->loggedInOn($event->occurredOn());

        $this->users->save($user);
    }
}
