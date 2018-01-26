<?php

declare(strict_types=1);

namespace Damax\User\Domain\Model;

use Ramsey\Uuid\UuidInterface;

interface IdGenerator
{
    public function nextId(): UuidInterface;
}
