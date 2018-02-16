<?php

declare(strict_types=1);

namespace Damax\User\Application\Exception;

use RuntimeException;

class ActionRequestExpired extends RuntimeException
{
    public static function withToken(string $token): self
    {
        return new self(sprintf('Action request with token "%s" is expired.', $token));
    }
}
