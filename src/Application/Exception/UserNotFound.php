<?php

declare(strict_types=1);

namespace Damax\User\Application\Exception;

use RuntimeException;

class UserNotFound extends RuntimeException
{
    public static function byId(string $id): self
    {
        return new static(sprintf('User by id "%s" not found.', $id));
    }

    public static function byEmail(string $email): self
    {
        return new static(sprintf('User by email "%s" not found.', $email));
    }

    public static function byMobilePhone(string $mobilePhone): self
    {
        return new static(sprintf('User by mobile phone "%s" not found.', $mobilePhone));
    }
}
