<?php

declare(strict_types=1);

namespace Damax\User\Application\Exception;

use RuntimeException;

class UserAlreadyExists extends RuntimeException
{
    public static function withEmail(string $email): self
    {
        return new self(sprintf('User with email "%s" already exists.', $email));
    }

    public static function withMobilePhone(string $mobilePhone): self
    {
        return new self(sprintf('User with mobile phone "%s" already exists.', $mobilePhone));
    }
}
