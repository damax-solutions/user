<?php

declare(strict_types=1);

namespace Damax\User\Tests\Domain\Listener;

use Damax\User\Domain\Event\UserRegistered;
use Damax\User\Domain\Listener\MailerListener;
use Damax\User\Domain\Mailer\Mailer;
use Damax\User\Domain\Model\UserRepository;
use Damax\User\Tests\Domain\Model\JohnDoeUser;
use DateTime;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Ramsey\Uuid\Uuid;

class MailerListenerTest extends TestCase
{
    /**
     * @var UserRepository|PHPUnit_Framework_MockObject_MockObject
     */
    private $users;

    /**
     * @var Mailer|PHPUnit_Framework_MockObject_MockObject
     */
    private $mailer;

    /**
     * @var MailerListener
     */
    private $listener;

    protected function setUp()
    {
        $this->users = $this->createMock(UserRepository::class);
        $this->mailer = $this->createMock(Mailer::class);
        $this->listener = new MailerListener($this->users, $this->mailer);
    }

    /**
     * @test
     */
    public function it_skips_sending_registration_email_on_missing_user()
    {
        $userId = Uuid::fromString('b1feaba3-7425-43c7-974a-6bb6622898ab');

        $this->users
            ->expects($this->once())
            ->method('byId')
            ->with($this->identicalTo($userId))
        ;
        $this->mailer
            ->expects($this->never())
            ->method('sendRegistrationEmail')
        ;

        $this->listener->onUserRegistered(new UserRegistered($userId, new DateTime()));
    }

    /**
     * @test
     */
    public function it_sends_registration_email()
    {
        $userId = Uuid::fromString('ce08c4e8-d9eb-435b-9eab-edc252b450e1');

        $this->users
            ->expects($this->once())
            ->method('byId')
            ->with($this->identicalTo($userId))
            ->willReturn($user = new JohnDoeUser())
        ;
        $this->mailer
            ->expects($this->once())
            ->method('sendRegistrationEmail')
            ->with($this->identicalTo($user))
        ;

        $this->listener->onUserRegistered(new UserRegistered($userId, new DateTime()));
    }
}
