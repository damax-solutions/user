<?php

declare(strict_types=1);

namespace Damax\User\Domain\Mailer;

use Assert\Assert;
use Damax\User\Domain\Model\User;
use Psr\Log\LoggerInterface;

class DebugMailer implements Mailer
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function sendRegistrationEmail(User $user): void
    {
        $this->logger->debug('Registration email sent.', [
            'email' => (string) $user->email(),
            'mobile' => (string) $user->mobilePhone(),
            'first_name' => $user->name()->firstName(),
            'last_name' => $user->name()->lastName(),
            'middle_name' => $user->name()->middleName(),
        ]);
    }

    public function sendPasswordResetEmail(User $user, array $context): void
    {
        Assert::that($context)->keyIsset('token');

        $this->logger->debug('Password reset email sent.', [
            'email' => (string) $user->email(),
            'mobile' => (string) $user->mobilePhone(),
            'token' => $context['token'],
        ]);
    }
}
