<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Bundle\Listener;

use Damax\User\Application\Command\RecordLogin;
use Damax\User\Application\Service\UserLoginService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\RememberMeToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class RecordLoginListener implements EventSubscriberInterface
{
    private $loginService;
    private $loginRouteName;

    public static function getSubscribedEvents(): array
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => 'onInteractiveLogin',
        ];
    }

    public function __construct(UserLoginService $loginService, string $loginRouteName = 'api_security_login')
    {
        $this->loginService = $loginService;
        $this->loginRouteName = $loginRouteName;
    }

    public function onInteractiveLogin(InteractiveLoginEvent $event)
    {
        $token = $event->getAuthenticationToken();

        if ($token instanceof RememberMeToken) {
            return;
        }

        $request = $event->getRequest();

        if ($request->attributes->get('_route') !== $this->loginRouteName) {
            return;
        }

        $command = new RecordLogin();
        $command->userId = $token->getUser()->getUsername();
        $command->clientIp = $request->getClientIp();
        $command->serverIp = $request->server->get('SERVER_ADDR', 'unknown');
        $command->userAgent = $request->server->get('HTTP_USER_AGENT', 'unknown');

        $this->loginService->recordLogin($command);
    }
}
