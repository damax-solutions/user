<?php

declare(strict_types=1);

namespace Damax\User\Tests\Domain\Mailer;

use Damax\User\Domain\Mailer\DebugMailer;
use Damax\User\Tests\Domain\Model\JohnDoeUser;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Psr\Log\LoggerInterface;

class DebugMailerTest extends TestCase
{
    /**
     * @var LoggerInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private $logger;

    /**
     * @var DebugMailer
     */
    private $mailer;

    protected function setUp()
    {
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->mailer = new DebugMailer($this->logger);
    }

    /**
     * @test
     */
    public function it_sends_registration_email()
    {
        $this->logger
            ->expects($this->once())
            ->method('debug')
            ->with('Registration email sent.', [
                'email' => 'john.doe@domain.abc',
                'mobile' => '+123',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'middle_name' => null,
            ])
        ;

        $this->mailer->sendRegistrationEmail(new JohnDoeUser());
    }

    /**
     * @test
     */
    public function it_sends_password_reset_email()
    {
        $this->logger
            ->expects($this->once())
            ->method('debug')
            ->with('Password reset email sent.', [
                'email' => 'john.doe@domain.abc',
                'mobile' => '+123',
                'token' => 'XYZ',
            ])
        ;

        $this->mailer->sendPasswordResetEmail(new JohnDoeUser(), ['token' => 'XYZ']);
    }

    /**
     * @test
     */
    public function it_sends_email_confirmation_email()
    {
        $this->logger
            ->expects($this->once())
            ->method('debug')
            ->with('Email confirmation email sent.', [
                'email' => 'john.doe@domain.abc',
                'mobile' => '+123',
                'token' => 'XYZ',
            ])
        ;

        $this->mailer->sendEmailConfirmationEmail(new JohnDoeUser(), ['token' => 'XYZ']);
    }
}
