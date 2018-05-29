<?php

declare(strict_types=1);

namespace Damax\User\Tests\Application\Service;

use Damax\Common\Domain\Transaction\DummyTransactionManager;
use Damax\User\Application\Command\ChangePassword;
use Damax\User\Application\Command\ResetPassword;
use Damax\User\Application\Dto\PasswordResetDto;
use Damax\User\Application\Dto\PasswordResetRequestDto;
use Damax\User\Application\Exception\ActionRequestExpired;
use Damax\User\Application\Exception\ActionRequestNotFound;
use Damax\User\Application\Exception\UserNotFound;
use Damax\User\Application\Service\PasswordService;
use Damax\User\Doctrine\Orm\UserRepository;
use Damax\User\Domain\Model\ActionRequest;
use Damax\User\Domain\Model\ActionRequestRepository;
use Damax\User\Domain\Model\Password;
use Damax\User\Domain\Password\Encoder;
use Damax\User\Domain\TokenGenerator\FixedTokenGenerator;
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
     * @var ActionRequestRepository|PHPUnit_Framework_MockObject_MockObject
     */
    private $requests;

    /**
     * @var PasswordService
     */
    private $service;

    protected function setUp()
    {
        $this->users = $this->createMock(UserRepository::class);
        $this->encoder = $this->createMock(Encoder::class);
        $this->requests = $this->createMock(ActionRequestRepository::class);
        $this->service = new PasswordService($this->users, $this->encoder, $this->requests, new FixedTokenGenerator('token'), new DummyTransactionManager());
    }

    /**
     * @test
     */
    public function it_throws_exception_when_changing_password_for_missing_user()
    {
        $command = new ChangePassword();
        $command->userId = 'john.doe@domain.abc';
        $command->password = '123456';

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
        $command->password = '123456';

        $this->service->changePassword($command);
        $this->assertSame($password, $user->password());
    }

    /**
     * @test
     */
    public function it_requests_password_reset()
    {
        $dto = new PasswordResetRequestDto();
        $dto->userId = 'john.doe@domain.abc';

        $user = new JohnDoeUser();

        /** @var ActionRequest $request */
        $request = null;

        $this->users
            ->expects($this->once())
            ->method('byEmail')
            ->with('john.doe@domain.abc')
            ->willReturn($user)
        ;
        $this->requests
            ->expects($this->once())
            ->method('save')
            ->willReturnCallback(function (ActionRequest $actionRequest) use (&$request) {
                $request = $actionRequest;
            })
        ;

        $this->service->requestPasswordReset($dto);

        $this->assertEquals('token', $request->token());
        $this->assertSame($user, $request->user());
        $this->assertEquals('password_reset', $request->type());
    }

    /**
     * @test
     */
    public function it_throws_exception_when_resetting_password_for_missing_request()
    {
        $dto = new PasswordResetDto();
        $dto->token = 'xyz';

        $this->users
            ->expects($this->never())
            ->method('save')
        ;
        $this->requests
            ->expects($this->never())
            ->method('remove')
        ;

        $this->expectException(ActionRequestNotFound::class);
        $this->expectExceptionMessage('Action request by token "xyz" not found.');

        $this->service->resetPassword($dto);
    }

    /**
     * @test
     */
    public function it_throws_exception_when_resetting_password_for_expired_request()
    {
        $dto = new PasswordResetDto();
        $dto->token = 'xyz';

        $user = new JohnDoeUser();
        $request = ActionRequest::resetPassword(new FixedTokenGenerator('xyz'), $user, -1);

        $this->requests
            ->expects($this->once())
            ->method('byToken')
            ->with('xyz')
            ->willReturnOnConsecutiveCalls($request)
        ;

        $this->expectException(ActionRequestExpired::class);
        $this->expectExceptionMessage('Action request with token "xyz" is expired.');

        $this->service->resetPassword($dto);
    }

    /**
     * @test
     */
    public function it_resets_password()
    {
        $dto = new PasswordResetDto();
        $dto->token = 'xyz';
        $dto->newPassword = 'new_pass';

        $user = new JohnDoeUser();
        $request = ActionRequest::resetPassword(new FixedTokenGenerator('xyz'), $user);

        $this->requests
            ->expects($this->once())
            ->method('byToken')
            ->with('xyz')
            ->willReturnOnConsecutiveCalls($request)
        ;
        $this->encoder
            ->expects($this->once())
            ->method('encode')
            ->with('new_pass')
            ->willReturn($password = Password::valid3Months('qwe', '123'))
        ;
        $this->users
            ->expects($this->once())
            ->method('save')
            ->with($this->identicalTo($user))
        ;
        $this->requests
            ->expects($this->once())
            ->method('remove')
            ->with($this->identicalTo($request))
        ;

        $this->service->resetPassword($dto);

        $this->assertSame($password, $user->password());
    }
}
