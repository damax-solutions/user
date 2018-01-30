<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Bundle\Listener;

use Damax\User\Bridge\Symfony\Security\User;
use Locale;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Translation\TranslatorInterface;

class LocaleListener
{
    private $tokenStorage;
    private $translator;

    public function __construct(TokenStorageInterface $tokenStorage, TranslatorInterface $translator)
    {
        $this->tokenStorage = $tokenStorage;
        $this->translator = $translator;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (null !== $token = $this->tokenStorage->getToken()) {
            $user = $token->getUser();

            if ($user instanceof User) {
                Locale::setDefault($user->getLocale());

                $this->translator->setLocale($user->getLocale());
            }
        }
    }
}
