<?php

declare(strict_types=1);

namespace Damax\User\Domain\Event;

use DateTimeInterface;
use Ramsey\Uuid\UuidInterface;

class LoginRecorded extends UserEvent
{
    private $clientIp;
    private $userAgent;

    public function __construct(UuidInterface $userId, string $clientIp, string $userAgent, DateTimeInterface $occurredOn)
    {
        parent::__construct($userId, $occurredOn);

        $this->clientIp = $clientIp;
        $this->userAgent = $userAgent;
    }

    public function clientIp(): string
    {
        return $this->clientIp;
    }

    public function userAgent(): string
    {
        return $this->userAgent;
    }
}
