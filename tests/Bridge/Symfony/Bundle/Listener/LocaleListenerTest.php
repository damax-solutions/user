<?php

declare(strict_types=1);

namespace Damax\User\Tests\Bridge\Symfony\Bundle\Listener;

use Damax\User\Bridge\Symfony\Bundle\Listener\LocaleListener;
use Damax\User\Tests\Bridge\Symfony\Security\UserFactory;
use Locale;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Translation\TranslatorInterface;

class LocaleListenerTest extends TestCase
{
    /**
     * @var TokenStorageInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private $tokenStorage;

    /**
     * @var TranslatorInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private $translator;

    /**
     * @var LocaleListener
     */
    private $listener;

    protected function setUp()
    {
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->translator = $this->createMock(TranslatorInterface::class);
        $this->listener = new LocaleListener($this->tokenStorage, $this->translator);
    }

    /**
     * @test
     */
    public function it_skips_locale_settings_on_missing_token()
    {
        $this->dispatchEvent();

        $this->assertNull($this->translator->getLocale());
    }

    /**
     * @test
     */
    public function it_sets_locale()
    {
        $user = (new UserFactory())->create();

        $this->tokenStorage
            ->expects($this->once())
            ->method('getToken')
            ->willReturn(new UsernamePasswordToken($user, 'qwerty', 'main'))
        ;
        $this->translator
            ->expects($this->once())
            ->method('setLocale')
            ->with('ru')
        ;

        $this->dispatchEvent();

        $this->assertEquals('ru', Locale::getDefault());
    }

    private function dispatchEvent()
    {
        /** @var GetResponseEvent $event */
        $event = $this->createMock(GetResponseEvent::class);

        $this->listener->onKernelRequest($event);
    }
}
