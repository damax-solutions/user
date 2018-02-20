<?php

declare(strict_types=1);

namespace Damax\User\Domain\Event;

use DateTimeInterface;
use Ramsey\Uuid\UuidInterface;

abstract class ActionRequestedEvent extends UserEvent
{
    private $token;

    public function __construct(UuidInterface $userId, string $token, DateTimeInterface $occurredOn)
    {
        parent::__construct($userId, $occurredOn);

        $this->token = $token;
    }

    public function token(): string
    {
        return $this->token;
    }
}
