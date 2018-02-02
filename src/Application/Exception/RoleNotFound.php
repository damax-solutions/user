<?php

declare(strict_types=1);

namespace Damax\User\Application\Exception;

use RuntimeException;

class RoleNotFound extends RuntimeException
{
    public static function byCode(string $code): self
    {
        return new self(sprintf('Role by code "%s" not found.', $code));
    }
}
