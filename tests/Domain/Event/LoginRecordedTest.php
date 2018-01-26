<?php

declare(strict_types=1);

namespace Damax\User\Tests\Domain\Event;

use Damax\User\Domain\Event\LoginRecorded;
use DateTime;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class LoginRecordedTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_event()
    {
        $userId = Uuid::fromString('ce08c4e8-d9eb-435b-9eab-edc252b450e1');

        $event = new LoginRecorded($userId, '192.168.1.100', 'Chrome', $occurredOn = new DateTime());

        $this->assertSame($userId, $event->userId());
        $this->assertEquals('192.168.1.100', $event->clientIp());
        $this->assertEquals('Chrome', $event->userAgent());
        $this->assertSame($occurredOn, $event->occurredOn());
    }
}
