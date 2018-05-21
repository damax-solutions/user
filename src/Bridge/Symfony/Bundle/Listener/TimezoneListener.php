<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Bundle\Listener;

use Damax\User\Bridge\Symfony\Security\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TimezoneListener implements EventSubscriberInterface
{
    private $tokenStorage;

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 4],
        ];
    }

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        if (null !== $token = $this->tokenStorage->getToken()) {
            $user = $token->getUser();

            if ($user instanceof User) {
                date_default_timezone_set($user->getTimezone());
            }
        }
    }
}
