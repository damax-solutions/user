<?php

declare(strict_types=1);

namespace Damax\User\Tests\Domain\Listener;

use Damax\User\Domain\Event\LoginRecorded;
use Damax\User\Domain\Listener\UserLoginListener;
use Damax\User\Domain\Model\UserRepository;
use Damax\User\Tests\Domain\Model\JohnDoeUser;
use DateTime;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Ramsey\Uuid\Uuid;

class UserLoginListenerTest extends TestCase
{
    /**
     * @var UserRepository|PHPUnit_Framework_MockObject_MockObject
     */
    private $users;

    /**
     * @var UserLoginListener
     */
    private $listener;

    protected function setUp()
    {
        $this->users = $this->createMock(UserRepository::class);
        $this->listener = new UserLoginListener($this->users);
    }

    /**
     * @test
     */
    public function it_skips_login_recording_on_missing_user()
    {
        $userId = Uuid::fromString('b1feaba3-7425-43c7-974a-6bb6622898ab');

        $this->users
            ->expects($this->once())
            ->method('byId')
            ->with($this->identicalTo($userId))
        ;
        $this->users
            ->expects($this->never())
            ->method('save')
        ;

        $this->listener->onLoginRecorded(new LoginRecorded($userId, '192.168.1.100', 'Chrome', new DateTime()));
    }

    /**
     * @test
     */
    public function it_records_login()
    {
        $userId = Uuid::fromString('ce08c4e8-d9eb-435b-9eab-edc252b450e1');

        $this->users
            ->expects($this->once())
            ->method('byId')
            ->with($this->identicalTo($userId))
            ->willReturn($user = new JohnDoeUser())
        ;
        $this->users
            ->method('save')
            ->with($this->identicalTo($user))
        ;

        $this->listener->onLoginRecorded(new LoginRecorded($userId, '192.168.1.100', 'Chrome', $occurredOn = new DateTime()));

        $this->assertSame($occurredOn, $user->lastLoginAt());
    }
}
