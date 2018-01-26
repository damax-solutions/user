<?php

declare(strict_types=1);

namespace Damax\User\Domain\Event;

use DateTimeInterface;
use Ramsey\Uuid\UuidInterface;

class LoginRecorded
{
    private $userId;
    private $clientIp;
    private $userAgent;
    private $occurredOn;

    public function __construct(UuidInterface $userId, string $clientIp, string $userAgent, DateTimeInterface $occurredOn)
    {
        $this->userId = $userId;
        $this->clientIp = $clientIp;
        $this->userAgent = $userAgent;
        $this->occurredOn = $occurredOn;
    }

    public function userId(): UuidInterface
    {
        return $this->userId;
    }

    public function clientIp(): string
    {
        return $this->clientIp;
    }

    public function userAgent(): string
    {
        return $this->userAgent;
    }

    public function occurredOn(): DateTimeInterface
    {
        return $this->occurredOn;
    }
}
