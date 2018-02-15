<?php

declare(strict_types=1);

namespace Damax\User\Domain\Mailer;

use Damax\User\Domain\Model\User;

interface Mailer
{
    public function sendRegistrationEmail(User $user): void;
}
