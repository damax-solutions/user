<?php

declare(strict_types=1);

namespace Damax\User\Domain\Event;

use DateTimeInterface;
use Ramsey\Uuid\UuidInterface;

abstract class UserEvent
{
    private $userId;
    private $occurredOn;

    public function __construct(UuidInterface $userId, DateTimeInterface $occurredOn)
    {
        $this->userId = $userId;
        $this->occurredOn = $occurredOn;
    }

    public function userId(): UuidInterface
    {
        return $this->userId;
    }

    public function occurredOn(): DateTimeInterface
    {
        return $this->occurredOn;
    }
}
