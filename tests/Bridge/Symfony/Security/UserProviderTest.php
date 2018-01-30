<?php

declare(strict_types=1);

namespace Damax\User\Tests\Bridge\Symfony\Security;

use Damax\User\Bridge\Symfony\Security\User;
use Damax\User\Bridge\Symfony\Security\UserAssembler;
use Damax\User\Bridge\Symfony\Security\UserProvider;
use Damax\User\Domain\Model\Email;
use Damax\User\Domain\Model\MobilePhone;
use Damax\User\Domain\Model\UserRepository;
use Damax\User\Tests\Domain\Model\JohnDoeUser;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\User as SymfonyUser;
use Symfony\Component\Security\Core\User\UserInterface;

class UserProviderTest extends TestCase
{
    /**
     * @var UserRepository|PHPUnit_Framework_MockObject_MockObject
     */
    private $repository;

    /**
     * @var UserAssembler|PHPUnit_Framework_MockObject_MockObject
     */
    private $assembler;

    /**
     * @var UserProvider
     */
    private $provider;

    protected function setUp()
    {
        $this->repository = $this->createMock(UserRepository::class);
        $this->assembler = $this->createMock(UserAssembler::class);
        $this->provider = new UserProvider($this->repository, $this->assembler);
    }

    /**
     * @test
     */
    public function it_supports_user_class()
    {
        $this->assertTrue($this->provider->supportsClass(User::class));
        $this->assertFalse($this->provider->supportsClass(SymfonyUser::class));
    }

    /**
     * @test
     */
    public function it_throws_exception_on_refreshing_unsupported_user()
    {
        $this->expectException(UnsupportedUserException::class);
        $this->expectExceptionMessage('User of type "Symfony\Component\Security\Core\User\User" is not supported.');

        $this->provider->refreshUser(new SymfonyUser('john.doe@domain.abc', 'qwerty'));
    }

    /**
     * @test
     */
    public function it_throws_exception_when_loading_missing_user_by_email()
    {
        $this->expectException(UsernameNotFoundException::class);
        $this->expectExceptionMessage('User "john.doe@domain.abc" not found.');

        $this->repository
            ->expects($this->once())
            ->method('byEmail')
            ->with(Email::fromString('john.doe@domain.abc'))
        ;

        $this->provider->loadUserByUsername('john.doe@domain.abc');
    }

    /**
     * @test
     */
    public function it_throws_exception_when_loading_missing_user_by_mobile_phone()
    {
        $this->expectException(UsernameNotFoundException::class);
        $this->expectExceptionMessage('User "123" not found.');

        $this->repository
            ->expects($this->once())
            ->method('byMobilePhone')
            ->with(MobilePhone::fromString('123'))
        ;

        $this->provider->loadUserByUsername('123');
    }

    /**
     * @test
     */
    public function it_loads_user_by_email()
    {
        $johndoe = new JohnDoeUser();

        $this->repository
            ->expects($this->once())
            ->method('byEmail')
            ->with(Email::fromString('john.doe@domain.abc'))
            ->willReturn($johndoe)
        ;

        $this->assembler
            ->method('assemble')
            ->with($this->identicalTo($johndoe))
            ->willReturn($user = $this->createMock(UserInterface::class))
        ;

        $this->assertSame($user, $this->provider->loadUserByUsername('john.doe@domain.abc'));
    }

    /**
     * @test
     */
    public function it_loads_user_by_mobile_phone()
    {
        $johndoe = new JohnDoeUser();

        $this->repository
            ->expects($this->once())
            ->method('byMobilePhone')
            ->with(MobilePhone::fromString('123'))
            ->willReturn($johndoe)
        ;

        $this->assembler
            ->method('assemble')
            ->with($this->identicalTo($johndoe))
            ->willReturn($user = $this->createMock(UserInterface::class))
        ;

        $this->assertSame($user, $this->provider->loadUserByUsername('123'));
    }

    /**
     * @test
     */
    public function it_refreshes_user()
    {
        $johndoe = new JohnDoeUser();

        $this->repository
            ->expects($this->once())
            ->method('byMobilePhone')
            ->with(MobilePhone::fromString('123'))
            ->willReturn($johndoe)
        ;

        $this->assembler
            ->method('assemble')
            ->with($this->identicalTo($johndoe))
            ->willReturn($user = $this->createMock(UserInterface::class))
        ;

        $this->assertSame($user, $this->provider->refreshUser((new UserFactory())->create()));
    }
}
