<?php

declare(strict_types=1);

namespace Damax\User\Tests\Application\Service;

use Damax\User\Application\Command\RegisterUser;
use Damax\User\Application\Dto\Assembler;
use Damax\User\Application\Dto\UserDto;
use Damax\User\Application\Service\RegistrationService;
use Damax\User\Domain\Model\UserFactory;
use Damax\User\Domain\Model\UserRepository;
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
        $this->service = new RegistrationService($this->userFactory, $this->users, $this->assembler);
    }

    /**
     * @test
     */
    public function it_registers_user()
    {
        $command = new RegisterUser();

        $this->userFactory
            ->expects($this->once())
            ->method('create')
            ->with($this->identicalTo($command))
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
