<?php

declare(strict_types=1);

namespace Damax\User\Tests\Application\Service;

use Damax\User\Application\Command\ChangePassword;
use Damax\User\Application\Exception\UserNotFound;
use Damax\User\Application\Service\PasswordService;
use Damax\User\Doctrine\Orm\UserRepository;
use Damax\User\Domain\Model\Password;
use Damax\User\Domain\Password\Encoder;
use Damax\User\Tests\Domain\Model\JohnDoeUser;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class PasswordServiceTest extends TestCase
{
    /**
     * @var UserRepository|PHPUnit_Framework_MockObject_MockObject
     */
    private $users;

    /**
     * @var Encoder|PHPUnit_Framework_MockObject_MockObject
     */
    private $encoder;

    /**
     * @var PasswordService
     */
    private $service;

    protected function setUp()
    {
        $this->users = $this->createMock(UserRepository::class);
        $this->encoder = $this->createMock(Encoder::class);
        $this->service = new PasswordService($this->users, $this->encoder);
    }

    /**
     * @test
     */
    public function it_throws_exception_when_changing_password_for_missing_user()
    {
        $command = new ChangePassword();
        $command->userId = 'john.doe@domain.abc';
        $command->newPassword = '123456';

        $this->expectException(UserNotFound::class);
        $this->expectExceptionMessage('User by email "john.doe@domain.abc" not found.');

        $this->service->changePassword($command);
    }

    /**
     * @test
     */
    public function it_changes_password()
    {
        $user = new JohnDoeUser();

        $this->users
            ->expects($this->once())
            ->method('byEmail')
            ->with('john.doe@domain.abc')
            ->willReturn($user)
        ;
        $this->users
            ->expects($this->once())
            ->method('save')
            ->with($this->identicalTo($user))
        ;
        $this->encoder
            ->expects($this->once())
            ->method('encode')
            ->with('123456')
            ->willReturn($password = Password::valid3Months('qwe', '123'))
        ;

        $command = new ChangePassword();
        $command->userId = 'john.doe@domain.abc';
        $command->newPassword = '123456';

        $this->service->changePassword($command);
        $this->assertSame($password, $user->password());
    }
}
