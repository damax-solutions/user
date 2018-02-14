<?php

declare(strict_types=1);

namespace Damax\User\Domain\Listener;

use Damax\User\Domain\Event\UserRegistered;
use Damax\User\Domain\Mailer\RegistrationMailer;
use Damax\User\Domain\Model\UserRepository;

class UserMailerListener
{
    private $users;
    private $mailer;

    public function __construct(UserRepository $users, RegistrationMailer $mailer)
    {
        $this->users = $users;
        $this->mailer = $mailer;
    }

    public function onUserRegistered(UserRegistered $event)
    {
        if (null === $user = $this->users->byId($event->userId())) {
            return;
        }

        $this->mailer->sendRegistrationEmail($user);
    }
}
