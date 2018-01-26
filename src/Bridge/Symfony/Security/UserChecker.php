<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Security;

use Symfony\Component\Security\Core\User\UserChecker as SymfonyUserChecker;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker extends SymfonyUserChecker
{
    public function checkPostAuth(UserInterface $user)
    {
        // Do not show error on expired credentials.
        // Force password change instead.
    }
}
