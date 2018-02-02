<?php

declare(strict_types=1);

namespace Damax\User\Application\Exception;

use RuntimeException;

class PermissionNotFound extends RuntimeException
{
    public static function byCode(string $code): self
    {
        return new static(sprintf('Permission by code "%s" not found.', $code));
    }
}
