<?php

declare(strict_types=1);

namespace Damax\User\Domain\Model;

use Damax\User\Domain\TokenGenerator\TokenGenerator;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Inflector\Inflector;
use SimpleBus\Message\Recorder\ContainsRecordedMessages;
use SimpleBus\Message\Recorder\PrivateMessageRecorderCapabilities;

class ActionRequest implements ContainsRecordedMessages
{
    use PrivateMessageRecorderCapabilities;

    private const PASSWORD_RESET = 'password_reset';
    private const EMAIL_CONFIRMATION = 'email_confirmation';
    private const DEFAULT_TTL = 600;

    private $token;
    private $user;
    private $type;
    private $createdAt;
    private $expiresAt;

    public static function resetPassword(TokenGenerator $generator, User $user, int $ttl = self::DEFAULT_TTL): self
    {
        return new self($generator->generateToken(), $user, self::PASSWORD_RESET, $ttl);
    }

    public static function emailConfirmation(TokenGenerator $generator, User $user, int $ttl = self::DEFAULT_TTL): self
    {
        return new self($generator->generateToken(), $user, self::EMAIL_CONFIRMATION, $ttl);
    }

    public function token(): string
    {
        return $this->token;
    }

    public function user(): User
    {
        return $this->user;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function createdAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function expiresAt(): DateTimeInterface
    {
        return $this->expiresAt;
    }

    public function expired(): bool
    {
        return new DateTimeImmutable() > $this->expiresAt;
    }

    public function activePasswordReset(): bool
    {
        return self::PASSWORD_RESET === $this->type && !$this->expired();
    }

    public function activeEmailConfirmation(): bool
    {
        return self::EMAIL_CONFIRMATION === $this->type && !$this->expired();
    }

    private function __construct(string $token, User $user, string $type, int $ttl)
    {
        $this->token = $token;
        $this->user = $user;
        $this->type = $type;
        $this->createdAt = new DateTimeImmutable();
        $this->expiresAt = $this->createdAt->modify(sprintf('+%d seconds', $ttl));

        $eventClass = 'Damax\\User\\Domain\\Event\\' . Inflector::classify($type) . 'Requested';

        $this->record(new $eventClass($user->id(), $token, $this->createdAt));
    }
}
