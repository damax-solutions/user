<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Mailer;

use Damax\Common\Domain\Email\EmailRenderer;
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

    public function __construct(Swift_Mailer $swift, EmailRenderer $renderer, NameFormatter $nameFormatter, array $options)
    {
        $this->swift = $swift;
        $this->renderer = $renderer;
        $this->nameFormatter = $nameFormatter;
        $this->options = $options;
    }

    public function sendRegistrationEmail(User $user): void
    {
        $template = $this->renderer->renderTemplate($this->options['registration_template'], ['user' => $user]);

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

        $this->swift->send($message);
    }
}
