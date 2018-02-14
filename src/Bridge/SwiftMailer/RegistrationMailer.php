<?php

declare(strict_types=1);

namespace Damax\User\Bridge\SwiftMailer;

use Damax\User\Domain\Mailer\RegistrationMailer as RegistrationMailerInterface;
use Damax\User\Domain\Model\User;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig_Environment;

class RegistrationMailer implements RegistrationMailerInterface
{
    private $swift;
    private $urlGenerator;
    private $twig;

    public function __construct(Swift_Mailer $swift, UrlGeneratorInterface $urlGenerator, Twig_Environment $twig)
    {
        $this->swift = $swift;
        $this->urlGenerator = $urlGenerator;
        $this->twig = $twig;
    }

    public function sendRegistrationEmail(User $user): void
    {
        $message = (new Swift_Message())
            ->setSubject('Test')
            ->setFrom('info@damax.solutions')
            ->setTo('lakiboy83@gmail.com')
            ->setBody('Test email...')
        ;

        $this->swift->send($message);
    }
}
