<?php

declare(strict_types=1);

namespace Damax\User\Tests\Application\Service;

use Damax\User\Application\Command\RegisterUser;
use Damax\User\Application\Dto\Assembler;
use Damax\User\Application\Dto\UserDto;
use Damax\User\Application\Dto\UserRegistrationDto;
use Damax\User\Application\Exception\UserAlreadyExists;
use Damax\User\Application\Service\RegistrationService;
use Damax\User\Domain\Model\UserFactory;
use Damax\User\Domain\Model\UserRepository;
use Damax\User\Tests\Domain\Model\JaneDoeUser;
use Damax\User\Tests\Domain\Model\JohnDoeUser;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class RegistrationServiceTest extends TestCase
{
    /**
     * @var UserFactory|PHPUnit_Framework_MockObject_MockObject
     */
    private $userFactory;

    /**
     * @var UserRepository|PHPUnit_Framework_MockObject_MockObject
     */
    private $users;

    /**
     * @var Assembler|PHPUnit_Framework_MockObject_MockObject
     */
    private $assembler;

    /**
     * @var RegistrationService
     */
    private $service;

    protected function setUp()
    {
        $this->userFactory = $this->createMock(UserFactory::class);
        $this->users = $this->createMock(UserRepository::class);
        $this->assembler = $this->createMock(Assembler::class);
        $this->service = new RegistrationService($this->users, $this->userFactory, $this->assembler);
    }

    /**
     * @test
     */
    public function it_throws_exception_when_user_exists_with_email()
    {
        $command = new RegisterUser();
        $command->user = new UserRegistrationDto();
        $command->user->email = 'john.doe@domain.abc';

        $this->users
            ->method('byEmail')
            ->with('john.doe@domain.abc')
            ->willReturn(new JohnDoeUser())
        ;

        $this->expectException(UserAlreadyExists::class);
        $this->expectExceptionMessage('User with email "john.doe@domain.abc" already exists.');

        $this->service->registerUser($command);
    }

    /**
     * @test
     */
    public function it_throws_exception_when_user_exists_with_mobile_phone()
    {
        $command = new RegisterUser();
        $command->user = new UserRegistrationDto();
        $command->user->email = 'john.doe@domain.abc';
        $command->user->mobilePhone = '123';

        $this->users
            ->method('byEmail')
            ->with('john.doe@domain.abc')
        ;
        $this->users
            ->method('byMobilePhone')
            ->with('123')
            ->willReturn(new JohnDoeUser())
        ;

        $this->expectException(UserAlreadyExists::class);
        $this->expectExceptionMessage('User with mobile phone "123" already exists.');

        $this->service->registerUser($command);
    }

    /**
     * @test
     */
    public function it_registers_user()
    {
        $command = new RegisterUser();
        $command->creatorId = 'jane.doe@domain.abc';
        $command->user = new UserRegistrationDto();
        $command->user->email = 'john.doe@domain.abc';
        $command->user->mobilePhone = '123';

        $this->users
            ->method('byEmail')
            ->withConsecutive(
                ['john.doe@domain.abc'],
                ['jane.doe@domain.abc']
            )
            ->willReturnOnConsecutiveCalls(null, $creator = new JaneDoeUser())
        ;
        $this->userFactory
            ->expects($this->once())
            ->method('create')
            ->with($this->identicalTo($command->user), $this->identicalTo($creator))
            ->willReturn($user = new JohnDoeUser())
        ;
        $this->users
            ->expects($this->once())
            ->method('save')
            ->with($this->identicalTo($user))
        ;
        $this->assembler
            ->expects($this->once())
            ->method('toUserDto')
            ->with($this->identicalTo($user))
            ->willReturn($dto = new UserDto())
        ;

        $this->assertSame($dto, $this->service->registerUser($command));
    }
}
