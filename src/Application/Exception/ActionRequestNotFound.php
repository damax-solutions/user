<?php

declare(strict_types=1);

namespace Damax\User\Application\Exception;

use RuntimeException;

class ActionRequestNotFound extends RuntimeException
{
    public static function byToken(string $token): self
    {
        return new self(sprintf('Action request by token "%s" not found.', $token));
    }
}
