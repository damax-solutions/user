<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Mailer;

use Assert\Assert;
use Damax\Common\Domain\Email\EmailRenderer;
use Damax\Common\Domain\Email\Template;
use Damax\User\Domain\Mailer\Mailer;
use Damax\User\Domain\Model\User;
use Damax\User\Domain\NameFormatter\NameFormatter;
use Swift_Mailer;
use Swift_Message;

class SwiftMailer implements Mailer
{
    private $swift;
    private $renderer;
    private $nameFormatter;
    private $options;

    public function __construct(Swift_Mailer $swift, EmailRenderer $renderer, NameFormatter $nameFormatter, array $mailerOptions)
    {
        $this->swift = $swift;
        $this->renderer = $renderer;
        $this->nameFormatter = $nameFormatter;
        $this->options = $mailerOptions;
    }

    public function sendRegistrationEmail(User $user): void
    {
        $template = $this->renderer->renderTemplate($this->options['registration_template'], ['user' => $user]);

        $this->swift->send($this->buildMessage($user, $template));
    }

    public function sendPasswordResetEmail(User $user, array $context): void
    {
        Assert::that($context)->keyIsset('token');

        $template = $this->renderer->renderTemplate($this->options['password_reset_template'], [
            'user' => $user,
            'token' => $context['token'],
        ]);

        $this->swift->send($this->buildMessage($user, $template));
    }

    public function sendEmailConfirmationEmail(User $user, array $context): void
    {
        Assert::that($context)->keyIsset('token');

        $template = $this->renderer->renderTemplate($this->options['email_confirmation_template'], [
            'user' => $user,
            'token' => $context['token'],
        ]);

        $this->swift->send($this->buildMessage($user, $template));
    }

    private function buildMessage(User $user, Template $template): Swift_Message
    {
        $message = (new Swift_Message())
            ->setTo($user->email()->email(), $this->nameFormatter->full($user->name()))
            ->setSubject($template->subject())
            ->setBody($template->text())
            ->setFrom($this->options['sender_email'], $this->options['sender_name'] ?? null)
        ;

        if ($template->html()) {
            $message
                ->setBody($template->html(), 'text/html')
                ->addPart($template->text(), 'text/plain')
            ;
        }

        return $message;
    }
}
