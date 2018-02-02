<?php

declare(strict_types=1);

namespace Damax\User\Tests\Application\Service;

use Damax\User\Application\Command\DisableUser;
use Damax\User\Application\Command\EnableUser;
use Damax\User\Application\Command\UpdateUser;
use Damax\User\Application\Dto\Assembler;
use Damax\User\Application\Dto\NameDto;
use Damax\User\Application\Dto\UserDto;
use Damax\User\Application\Service\UserService;
use Damax\User\Domain\Model\UserRepository;
use Damax\User\Tests\Domain\Model\JaneDoeUser;
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
        $editor = new JaneDoeUser();

        $user = new JohnDoeUser();

        $this->users
            ->expects($this->exactly(2))
            ->method('byId')
            ->withConsecutive(
                ['02158a54-0510-11e8-a654-005056806fb2'],
                ['ce08c4e8-d9eb-435b-9eab-edc252b450e1']
            )
            ->willReturnOnConsecutiveCalls($editor, $user)
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

        $command = new DisableUser();
        $command->userId = 'ce08c4e8-d9eb-435b-9eab-edc252b450e1';
        $command->editorId = '02158a54-0510-11e8-a654-005056806fb2';

        $this->assertTrue($user->enabled());

        $this->assertSame($dto, $this->service->disable($command));
        $this->assertFalse($user->enabled());
        $this->assertSame($editor, $user->updatedBy());
    }

    /**
     * @test
     */
    public function it_enables_user()
    {
        $editor = new JaneDoeUser();

        $user = new JohnDoeUser();
        $user->disable($user);

        $this->users
            ->expects($this->exactly(2))
            ->method('byId')
            ->withConsecutive(
                ['02158a54-0510-11e8-a654-005056806fb2'],
                ['ce08c4e8-d9eb-435b-9eab-edc252b450e1']
            )
            ->willReturnOnConsecutiveCalls($editor, $user)
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

        $command = new EnableUser();
        $command->userId = 'ce08c4e8-d9eb-435b-9eab-edc252b450e1';
        $command->editorId = '02158a54-0510-11e8-a654-005056806fb2';

        $this->assertFalse($user->enabled());

        $this->assertSame($dto, $this->service->enable($command));
        $this->assertTrue($user->enabled());
        $this->assertSame($editor, $user->updatedBy());
    }

    /**
     * @test
     */
    public function it_updates_user()
    {
        $command = new UpdateUser();
        $command->userId = 'ce08c4e8-d9eb-435b-9eab-edc252b450e1';
        $command->editorId = '02158a54-0510-11e8-a654-005056806fb2';
        $command->timezone = 'Europe/Moscow';
        $command->locale = 'ru';

        $command->name = new NameDto();
        $command->name->lastName = 'Smith';
        $command->name->firstName = 'John';

        $editor = new JaneDoeUser();
        $user = new JohnDoeUser();

        $this->users
            ->expects($this->exactly(2))
            ->method('byId')
            ->withConsecutive(
                ['02158a54-0510-11e8-a654-005056806fb2'],
                ['ce08c4e8-d9eb-435b-9eab-edc252b450e1']
            )
            ->willReturnOnConsecutiveCalls($editor, $user)
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

        $this->assertSame($dto, $this->service->update($command));

        $this->assertEquals('Europe/Moscow', $user->timezone()->id());
        $this->assertEquals('ru', $user->locale()->code());
        $this->assertSame($editor, $user->updatedBy());
        $this->assertEquals('John', $user->name()->firstName());
        $this->assertEquals('Smith', $user->name()->lastName());
        $this->assertNull($user->name()->middleName());
    }
}
