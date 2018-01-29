<?php

declare(strict_types=1);

namespace Damax\User\Tests\Application\Service;

use Damax\User\Application\Dto\Assembler;
use Damax\User\Application\Dto\UserDto;
use Damax\User\Application\Service\UserService;
use Damax\User\Domain\Model\UserRepository;
use Damax\User\Tests\Domain\Model\JohnDoeUser;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class UserServiceTest extends TestCase
{
    /**
     * @var UserRepository|PHPUnit_Framework_MockObject_MockObject
     */
    private $users;

    /**
     * @var Assembler|PHPUnit_Framework_MockObject_MockObject
     */
    private $assembler;

    /**
     * @var UserService
     */
    private $service;

    protected function setUp()
    {
        $this->users = $this->createMock(UserRepository::class);
        $this->assembler = $this->createMock(Assembler::class);
        $this->service = new UserService($this->users, $this->assembler);
    }

    /**
     * @test
     */
    public function it_fetches_user()
    {
        $user = new JohnDoeUser();

        $this->users
            ->expects($this->once())
            ->method('byId')
            ->with('ce08c4e8-d9eb-435b-9eab-edc252b450e1')
            ->willReturn($user)
        ;
        $this->assembler
            ->expects($this->once())
            ->method('toUserDto')
            ->with($this->identicalTo($user))
            ->willReturn($dto = new UserDto())
        ;

        $this->assertSame($dto, $this->service->fetch('ce08c4e8-d9eb-435b-9eab-edc252b450e1'));
    }

    /**
     * @test
     */
    public function it_fetches_range()
    {
        $user = new JohnDoeUser();

        $this->users
            ->expects($this->once())
            ->method('paginate')
            ->willReturn(new Pagerfanta(new ArrayAdapter(array_fill(0, 35, $user))))
        ;
        $this->assembler
            ->expects($this->exactly(10))
            ->method('toUserDto')
        ;

        $items = $this->service->fetchRange()->setMaxPerPage(10);

        $this->assertCount(35, $items);
        $this->assertContainsOnlyInstancesOf(UserDto::class, $array = iterator_to_array($items));
        $this->assertCount(10, $array);
    }

    /**
     * @test
     */
    public function it_disables_user()
    {
        $user = new JohnDoeUser();

        $this->users
            ->expects($this->once())
            ->method('byId')
            ->with('ce08c4e8-d9eb-435b-9eab-edc252b450e1')
            ->willReturn($user)
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

        $this->assertTrue($user->enabled());
        $this->assertSame($dto, $this->service->disable('ce08c4e8-d9eb-435b-9eab-edc252b450e1'));
        $this->assertFalse($user->enabled());
    }

    /**
     * @test
     */
    public function it_enables_user()
    {
        $user = new JohnDoeUser();
        $user->disable();

        $this->users
            ->expects($this->once())
            ->method('byId')
            ->with('ce08c4e8-d9eb-435b-9eab-edc252b450e1')
            ->willReturn($user)
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

        $this->assertFalse($user->enabled());
        $this->assertSame($dto, $this->service->enable('ce08c4e8-d9eb-435b-9eab-edc252b450e1'));
        $this->assertTrue($user->enabled());
    }
}
