<?php

declare(strict_types=1);

namespace Damax\User\Tests\Application\Service;

use Damax\User\Application\Exception\UserNotFound;
use Damax\User\Application\Service\UserServiceTrait;
use Damax\User\Domain\Model\User;
use Damax\User\Domain\Model\UserRepository;
use Damax\User\Tests\Domain\Model\JohnDoeUser;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class UserServiceTraitTest extends TestCase
{
    /**
     * @var UserRepository|PHPUnit_Framework_MockObject_MockObject
     */
    private $users;

    /**
     * @var UserServiceTrait
     */
    private $service;

    protected function setUp()
    {
        $this->users = $this->createMock(UserRepository::class);
        $this->service = new class($this->users) {
            use UserServiceTrait;

            private $users;

            public function __construct(UserRepository $users)
            {
                $this->users = $users;
            }

            public function fetchUser(string $userId): User
            {
                return $this->getUser($userId);
            }
        };
    }

    /**
     * @test
     */
    public function it_fetches_user_by_id()
    {
        $user = new JohnDoeUser();

        $this->users
            ->expects($this->once())
            ->method('byId')
            ->with('ce08c4e8-d9eb-435b-9eab-edc252b450e1')
            ->willReturn($user)
        ;

        $this->assertSame($user, $this->service->fetchUser('ce08c4e8-d9eb-435b-9eab-edc252b450e1'));
    }

    /**
     * @test
     */
    public function it_throws_exception_when_user_is_missing_by_id()
    {
        $this->expectException(UserNotFound::class);
        $this->expectExceptionMessage('User by id "ce08c4e8-d9eb-435b-9eab-edc252b450e1" not found.');

        $this->service->fetchUser('ce08c4e8-d9eb-435b-9eab-edc252b450e1');
    }

    /**
     * @test
     */
    public function it_fetches_user_by_email()
    {
        $user = new JohnDoeUser();

        $this->users
            ->expects($this->once())
            ->method('byEmail')
            ->with('john.doe@domain.abc')
            ->willReturn($user)
        ;

        $this->assertSame($user, $this->service->fetchUser('john.doe@domain.abc'));
    }

    /**
     * @test
     */
    public function it_throws_exception_when_user_is_missing_by_email()
    {
        $this->expectException(UserNotFound::class);
        $this->expectExceptionMessage('User by email "john.doe@domain.abc" not found.');

        $this->service->fetchUser('john.doe@domain.abc');
    }

    /**
     * @test
     */
    public function it_fetches_user_by_mobile_phone()
    {
        $user = new JohnDoeUser();

        $this->users
            ->expects($this->once())
            ->method('byMobilePhone')
            ->with('123')
            ->willReturn($user)
        ;

        $this->assertSame($user, $this->service->fetchUser('123'));
    }

    /**
     * @test
     */
    public function it_throws_exception_when_user_is_missing_by_mobile_phone()
    {
        $this->expectException(UserNotFound::class);
        $this->expectExceptionMessage('User by mobile phone "123" not found.');

        $this->service->fetchUser('123');
    }
}
