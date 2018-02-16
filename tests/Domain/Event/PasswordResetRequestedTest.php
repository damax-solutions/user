<?php

declare(strict_types=1);

namespace Damax\User\Tests\Domain\Event;

use Damax\User\Domain\Event\PasswordResetRequested;
use DateTime;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class PasswordResetRequestedTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_event()
    {
        $userId = Uuid::fromString('ce08c4e8-d9eb-435b-9eab-edc252b450e1');

        $event = new PasswordResetRequested($userId, 'XYZ', $occurredOn = new DateTime());

        $this->assertEquals('XYZ', $event->token());
    }
}
