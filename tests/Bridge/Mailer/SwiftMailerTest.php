<?php

declare(strict_types=1);

namespace Damax\User\Tests\Bridge\Mailer;

use Damax\Common\Domain\Email\EmailRenderer;
use Damax\Common\Domain\Email\Template;
use Damax\User\Bridge\Mailer\SwiftMailer;
use Damax\User\Domain\NameFormatter\JamesBondNameFormatter;
use Damax\User\Tests\Domain\Model\JohnDoeUser;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Swift_Mailer;
use Swift_Message;

class SwiftMailerTest extends TestCase
{
    /**
     * @var Swift_Mailer|PHPUnit_Framework_MockObject_MockObject
     */
    private $swift;

    /**
     * @var EmailRenderer|PHPUnit_Framework_MockObject_MockObject
     */
    private $renderer;

    /**
     * @var SwiftMailer
     */
    private $mailer;

    protected function setUp()
    {
        $this->swift = $this->createMock(Swift_Mailer::class);
        $this->renderer = $this->createMock(EmailRenderer::class);
        $this->mailer = new SwiftMailer($this->swift, $this->renderer, new JamesBondNameFormatter(), [
            'registration_template' => 'registration_template.twig',
            'password_reset_template' => 'password_reset_template.twig',
            'sender_email' => 'jane.doe@domain.abc',
            'sender_name' => 'Jane Doe',
        ]);
    }

    /**
     * @test
     */
    public function it_sends_registration_email()
    {
        $user = new JohnDoeUser();

        /** @var Swift_Message $message */
        $message = null;

        $this->renderer
            ->expects($this->once())
            ->method('renderTemplate')
            ->with('registration_template.twig', $this->identicalTo(['user' => $user]))
            ->willReturn(new Template('Subject', 'Text body', 'HTML body'))
        ;
        $this->swift
            ->expects($this->once())
            ->method('send')
            ->willReturnCallback(function (Swift_Message $msg) use (&$message) {
                $message = $msg;
            })
        ;

        $this->mailer->sendRegistrationEmail($user);

        $this->assertEquals(['jane.doe@domain.abc' => 'Jane Doe'], $message->getFrom());
        $this->assertEquals(['john.doe@domain.abc' => 'Doe, John Doe'], $message->getTo());
        $this->assertEquals('HTML body', $message->getBody());
        $this->assertEquals('Subject', $message->getSubject());
    }

    /**
     * @test
     */
    public function it_sends_password_reset_email()
    {
        $user = new JohnDoeUser();

        /** @var Swift_Message $message */
        $message = null;

        $this->renderer
            ->expects($this->once())
            ->method('renderTemplate')
            ->with('password_reset_template.twig', $this->identicalTo(['user' => $user, 'token' => 'XYZ']))
            ->willReturn(new Template('Subject', 'Text body', 'HTML body'))
        ;
        $this->swift
            ->expects($this->once())
            ->method('send')
            ->willReturnCallback(function (Swift_Message $msg) use (&$message) {
                $message = $msg;
            })
        ;

        $this->mailer->sendPasswordResetEmail($user, ['token' => 'XYZ']);

        $this->assertEquals(['jane.doe@domain.abc' => 'Jane Doe'], $message->getFrom());
        $this->assertEquals(['john.doe@domain.abc' => 'Doe, John Doe'], $message->getTo());
        $this->assertEquals('HTML body', $message->getBody());
        $this->assertEquals('Subject', $message->getSubject());
    }
}
