<?php

declare(strict_types=1);

namespace Damax\User\Tests\Bridge\Symfony\Bundle\Listener;

use Damax\User\Bridge\Symfony\Bundle\Listener\TimezoneListener;
use Damax\User\Tests\Bridge\Symfony\Security\UserFactory;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class TimezoneListenerTest extends TestCase
{
    /**
     * @var TokenStorageInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private $tokenStorage;

    /**
     * @var TimezoneListener
     */
    private $listener;

    /**
     * @var GetResponseEvent|PHPUnit_Framework_MockObject_MockObject
     */
    private $event;

    private $defaultTimezone;

    protected function setUp()
    {
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->listener = new TimezoneListener($this->tokenStorage);
        $this->event = $this->createMock(GetResponseEvent::class);
        $this->defaultTimezone = date_default_timezone_get();
    }

    protected function tearDown()
    {
        date_default_timezone_set($this->defaultTimezone);
    }

    /**
     * @test
     */
    public function it_skips_timezone_setting_on_non_master_request()
    {
        $this->event
            ->method('isMasterRequest')
            ->willReturn(false)
        ;
        $this->tokenStorage
            ->expects($this->never())
            ->method('getToken')
        ;

        $this->dispatchEvent();

        $this->assertEquals($this->defaultTimezone, date_default_timezone_get());
    }

    /**
     * @test
     */
    public function it_skips_timezone_settings_on_missing_token()
    {
        $this->event
            ->method('isMasterRequest')
            ->willReturn(true)
        ;
        $this->tokenStorage
            ->expects($this->once())
            ->method('getToken')
        ;

        $this->dispatchEvent();

        $this->assertEquals($this->defaultTimezone, date_default_timezone_get());
    }

    /**
     * @test
     */
    public function it_sets_timezone()
    {
        $user = (new UserFactory())->create();

        $this->event
            ->method('isMasterRequest')
            ->willReturn(true)
        ;
        $this->tokenStorage
            ->expects($this->once())
            ->method('getToken')
            ->willReturn(new UsernamePasswordToken($user, 'qwerty', 'main'))
        ;

        $this->dispatchEvent();

        $this->assertEquals('Europe/Riga', date_default_timezone_get());
    }

    private function dispatchEvent()
    {
        $this->listener->onKernelRequest($this->event);
    }
}
