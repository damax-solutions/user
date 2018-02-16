<?php

declare(strict_types=1);

namespace Damax\User\Domain\Listener;

use Damax\User\Domain\Event\PasswordResetRequested;
use Damax\User\Domain\Event\UserRegistered;
use Damax\User\Domain\Mailer\Mailer;
use Damax\User\Domain\Model\UserRepository;

class MailerListener
{
    private $users;
    private $mailer;

    public function __construct(UserRepository $users, Mailer $mailer)
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

    public function onPasswordResetRequested(PasswordResetRequested $event)
    {
        if (null === $user = $this->users->byId($event->userId())) {
            return;
        }

        $this->mailer->sendPasswordResetEmail($user, ['token' => $event->token()]);
    }
}
