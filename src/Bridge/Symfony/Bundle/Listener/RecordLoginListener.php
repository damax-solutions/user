<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Bundle\Listener;

use Damax\User\Application\Command\RecordLogin;
use Damax\User\Application\Service\UserLoginService;
use Symfony\Component\Security\Core\Authentication\Token\RememberMeToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class RecordLoginListener
{
    private $loginService;

    public function __construct(UserLoginService $loginService)
    {
        $this->loginService = $loginService;
    }

    public function onInteractiveLogin(InteractiveLoginEvent $event)
    {
        $token = $event->getAuthenticationToken();

        if ($token instanceof RememberMeToken) {
            return;
        }

        $request = $event->getRequest();

        $command = new RecordLogin();
        $command->userId = $token->getUser()->getUsername();
        $command->clientIp = $request->getClientIp();
        $command->serverIp = $request->server->get('SERVER_ADDR', 'unknown');
        $command->userAgent = $request->server->get('HTTP_USER_AGENT', 'unknown');

        $this->loginService->recordLogin($command);
    }
}
