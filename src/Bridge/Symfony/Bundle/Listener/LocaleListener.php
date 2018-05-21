<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Bundle\Listener;

use Damax\User\Bridge\Symfony\Security\User;
use InvalidArgumentException;
use Locale;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Translation\TranslatorInterface;

class LocaleListener implements EventSubscriberInterface
{
    private $tokenStorage;
    private $translator;

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 4],
        ];
    }

    public function __construct(TokenStorageInterface $tokenStorage, TranslatorInterface $translator)
    {
        $this->tokenStorage = $tokenStorage;
        $this->translator = $translator;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (null === $token = $this->tokenStorage->getToken()) {
            return;
        }

        $user = $token->getUser();

        if (!$user instanceof User) {
            return;
        }

        $locale = $user->getLocale();

        Locale::setDefault($locale);

        $event->getRequest()->setLocale($locale);

        try {
            $this->translator->setLocale($locale);
        } catch (InvalidArgumentException $e) {
            $this->translator->setLocale($event->getRequest()->getDefaultLocale());
        }
    }
}
