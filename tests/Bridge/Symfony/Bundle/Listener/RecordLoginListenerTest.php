<?php

declare(strict_types=1);

namespace Damax\User\Tests\Bridge\Symfony\Bundle\Listener;

use Damax\User\Application\Command\RecordLogin;
use Damax\User\Application\Dto\UserLoginDto;
use Damax\User\Application\Service\UserLoginService;
use Damax\User\Bridge\Symfony\Bundle\Listener\RecordLoginListener;
use Damax\User\Tests\Bridge\Symfony\Security\UserFactory;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\RememberMeToken;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class RecordLoginListenerTest extends TestCase
{
    /**
     * @var UserLoginService|PHPUnit_Framework_MockObject_MockObject
     */
    private $service;

    /**
     * @var RecordLoginListener
     */
    private $listener;

    protected function setUp()
    {
        $this->service = $this->createMock(UserLoginService::class);
        $this->listener = new RecordLoginListener($this->service);
    }

    /**
     * @test
     */
    public function it_skips_login_recording_for_unsupported_token()
    {
        $user = new User('username', 'qwerty');

        $token = new RememberMeToken($user, 'qwerty', 'main');
        $event = new InteractiveLoginEvent(new Request(), $token);

        $this->service
            ->expects($this->never())
            ->method('recordLogin')
        ;

        $this->listener->onInteractiveLogin($event);
    }

    /**
     * @test
     */
    public function it_skips_login_recording_on_non_login_route()
    {
        $request = new Request();
        $request->attributes->set('_route', 'random_route');

        $token = new UsernamePasswordToken($user = (new UserFactory())->create(), 'qwerty', 'main');
        $event = new InteractiveLoginEvent($request, $token);

        $this->service
            ->expects($this->never())
            ->method('recordLogin')
        ;

        $this->listener->onInteractiveLogin($event);
    }

    /**
     * @test
     */
    public function it_records_login()
    {
        $request = new Request();
        $request->attributes->set('_route', 'api_security_login');
        $request->server->set('REMOTE_ADDR', '192.168.99.100');
        $request->server->set('HTTP_USER_AGENT', 'Chrome');
        $request->server->set('SERVER_ADDR', '192.168.99.1');

        $token = new UsernamePasswordToken($user = (new UserFactory())->create(), 'qwerty', 'main');
        $event = new InteractiveLoginEvent($request, $token);

        /** @var RecordLogin $command */
        $command = null;

        $this->service
            ->method('recordLogin')
            ->willReturnCallback(function (RecordLogin $cmd) use (&$command) {
                $command = $cmd;

                return new UserLoginDto();
            })
        ;

        $this->listener->onInteractiveLogin($event);

        $this->assertEquals('123', $command->userId);
        $this->assertEquals('192.168.99.100', $command->clientIp);
        $this->assertEquals('192.168.99.1', $command->serverIp);
        $this->assertEquals('Chrome', $command->userAgent);
    }
}
