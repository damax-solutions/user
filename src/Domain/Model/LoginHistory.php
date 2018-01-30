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
    private $user;
    private $username;
    private $clientIp;
    private $serverIp;
    private $userAgent;
    private $createdAt;

    public function __construct(UuidInterface $id, User $user, string $username, string $clientIp, string $serverIp, string $userAgent)
    {
        $this->id = $id;
        $this->user = $user;
        $this->username = $username;
        $this->clientIp = $clientIp;
        $this->serverIp = $serverIp;
        $this->userAgent = $userAgent;
        $this->createdAt = new DateTimeImmutable();

        $this->record(new LoginRecorded($user->id(), $clientIp, $userAgent, $this->createdAt));
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }

    public function user(): User
    {
        return $this->user;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function clientIp(): string
    {
        return $this->clientIp;
    }

    public function serverIp(): string
    {
        return $this->serverIp;
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
