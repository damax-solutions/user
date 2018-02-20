<?php

declare(strict_types=1);

namespace Damax\User\Tests\Domain\Event;

use Damax\User\Domain\Event\UserEvent;
use DateTime;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class UserEventTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_event()
    {
        $userId = Uuid::fromString('ce08c4e8-d9eb-435b-9eab-edc252b450e1');

        $event = new class($userId, $occurredOn = new DateTime()) extends UserEvent {
        };

        $this->assertSame($userId, $event->userId());
        $this->assertSame($occurredOn, $event->occurredOn());
    }
}
