<?php

declare(strict_types=1);

namespace Damax\User\Tests\Application\Service;

use Damax\Common\Domain\Transaction\DummyTransactionManager;
use Damax\User\Application\Dto\EmailConfirmationDto;
use Damax\User\Application\Dto\EmailConfirmationRequestDto;
use Damax\User\Application\Exception\ActionRequestExpired;
use Damax\User\Application\Exception\ActionRequestNotFound;
use Damax\User\Application\Service\ConfirmationService;
use Damax\User\Domain\Model\ActionRequest;
use Damax\User\Domain\Model\ActionRequestRepository;
use Damax\User\Domain\Model\UserRepository;
use Damax\User\Domain\TokenGenerator\FixedTokenGenerator;
use Damax\User\Tests\Domain\Model\JohnDoeUser;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class ConfirmationServiceTest extends TestCase
{
    /**
     * @var UserRepository|PHPUnit_Framework_MockObject_MockObject
     */
    private $users;

    /**
     * @var ActionRequestRepository|PHPUnit_Framework_MockObject_MockObject
     */
    private $requests;

    /**
     * @var ConfirmationService
     */
    private $service;

    protected function setUp()
    {
        $this->users = $this->createMock(UserRepository::class);
        $this->requests = $this->createMock(ActionRequestRepository::class);
        $this->service = new ConfirmationService($this->users, $this->requests, new FixedTokenGenerator('token'), new DummyTransactionManager());
    }

    /**
     * @test
     */
    public function it_requests_email_confirmation()
    {
        $dto = new EmailConfirmationRequestDto();
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

        $this->service->requestEmailConfirmation($dto);

        $this->assertEquals('token', $request->token());
        $this->assertSame($user, $request->user());
        $this->assertEquals('email_confirmation', $request->type());
    }

    /**
     * @test
     */
    public function it_skips_email_confirmation_request_for_already_confirmed_email()
    {
        $dto = new EmailConfirmationRequestDto();
        $dto->userId = 'john.doe@domain.abc';

        $user = new JohnDoeUser();
        $user->confirmEmail();

        $this->users
            ->expects($this->once())
            ->method('byEmail')
            ->with('john.doe@domain.abc')
            ->willReturn($user)
        ;
        $this->requests
            ->expects($this->never())
            ->method('save')
        ;

        $this->service->requestEmailConfirmation($dto);
    }

    /**
     * @test
     */
    public function it_throws_exception_when_confirming_email_for_missing_request()
    {
        $dto = new EmailConfirmationDto();
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

        $this->service->confirmEmail($dto);
    }

    /**
     * @test
     */
    public function it_throws_exception_when_confirming_email_for_expired_request()
    {
        $dto = new EmailConfirmationDto();
        $dto->token = 'xyz';

        $user = new JohnDoeUser();
        $request = ActionRequest::emailConfirmation(new FixedTokenGenerator('xyz'), $user, -1);

        $this->requests
            ->expects($this->once())
            ->method('byToken')
            ->with('xyz')
            ->willReturnOnConsecutiveCalls($request)
        ;

        $this->expectException(ActionRequestExpired::class);
        $this->expectExceptionMessage('Action request with token "xyz" is expired.');

        $this->service->confirmEmail($dto);
    }

    /**
     * @test
     */
    public function it_confirms_email()
    {
        $dto = new EmailConfirmationDto();
        $dto->token = 'xyz';

        $user = new JohnDoeUser();
        $request = ActionRequest::emailConfirmation(new FixedTokenGenerator('xyz'), $user);

        $this->requests
            ->expects($this->once())
            ->method('byToken')
            ->with('xyz')
            ->willReturnOnConsecutiveCalls($request)
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

        $this->service->confirmEmail($dto);

        $this->assertTrue($user->email()->confirmed());
    }
}
