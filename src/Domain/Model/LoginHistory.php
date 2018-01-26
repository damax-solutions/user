<?php

declare(strict_types=1);

namespace Damax\User\Domain\Model;

use Damax\User\Domain\Event\LoginRecorded;
use DateTimeImmutable;
use DateTimeInterface;
use Ramsey\Uuid\UuidInterface;
use SimpleBus\Message\Recorder\ContainsRecordedMessages;
use SimpleBus\Message\Recorder\PrivateMessageRecorderCapabilities;

class LoginHistory implements ContainsRecordedMessages
{
    use PrivateMessageRecorderCapabilities;

    private $id;
    private $userId;
    private $clientIp;
    private $userAgent;
    private $createdAt;

    public function __construct(UuidInterface $id, UuidInterface $userId, string $clientIp, string $userAgent)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->clientIp = $clientIp;
        $this->userAgent = $userAgent;
        $this->createdAt = new DateTimeImmutable();

        $this->record(new LoginRecorded($userId, $clientIp, $userAgent, $this->createdAt));
    }

    public function id(): UuidInterface
    {
        return $this->id;
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

    public function createdAt(): DateTimeInterface
    {
        return $this->createdAt;
    }
}
