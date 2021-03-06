<?php

declare(strict_types=1);

namespace Damax\User\Tests\Application\Service;

use Damax\User\Application\Command\AssignUserRole;
use Damax\User\Application\Command\DisableUser;
use Damax\User\Application\Command\EnableUser;
use Damax\User\Application\Command\RemoveUserRole;
use Damax\User\Application\Command\UpdateUser;
use Damax\User\Application\Dto\Assembler;
use Damax\User\Application\Dto\NameDto;
use Damax\User\Application\Dto\UserDto;
use Damax\User\Application\Dto\UserInfoDto;
use Damax\User\Application\Service\UserService;
use Damax\User\Domain\Model\RoleRepository;
use Damax\User\Domain\Model\UserRepository;
use Damax\User\Tests\Domain\Model\AdminRole;
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
     * @var RoleRepository|PHPUnit_Framework_MockObject_MockObject
     */
    private $roles;

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
        $this->roles = $this->createMock(RoleRepository::class);
        $this->assembler = $this->createMock(Assembler::class);
        $this->service = new UserService($this->users, $this->roles, $this->assembler);
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

        $command->info = new UserInfoDto();
        $command->info->timezone = 'Europe/Moscow';
        $command->info->locale = 'ru';

        $command->info->name = new NameDto();
        $command->info->name->lastName = 'Smith';
        $command->info->name->firstName = 'John';

        $user = new JohnDoeUser();

        $this->users
            ->expects($this->exactly(1))
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

        $this->assertSame($dto, $this->service->update($command));

        $this->assertEquals('Europe/Moscow', $user->timezone()->id());
        $this->assertEquals('ru', $user->locale()->code());
        $this->assertEquals('John', $user->name()->firstName());
        $this->assertEquals('Smith', $user->name()->lastName());
        $this->assertNull($user->name()->middleName());
    }

    /**
     * @test
     */
    public function it_assigns_role()
    {
        $command = new AssignUserRole();
        $command->userId = 'ce08c4e8-d9eb-435b-9eab-edc252b450e1';
        $command->editorId = '02158a54-0510-11e8-a654-005056806fb2';
        $command->role = 'admin';

        $user = new JohnDoeUser();
        $editor = new JaneDoeUser();
        $admin = new AdminRole();

        $this->users
            ->expects($this->exactly(2))
            ->method('byId')
            ->withConsecutive(
                ['02158a54-0510-11e8-a654-005056806fb2'],
                ['ce08c4e8-d9eb-435b-9eab-edc252b450e1']
            )
            ->willReturnOnConsecutiveCalls($editor, $user)
        ;
        $this->roles
            ->expects($this->once())
            ->method('byCode')
            ->with('admin')
            ->willReturn($admin)
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

        $this->assertSame($dto, $this->service->assignRole($command));

        $this->assertSame($editor, $user->updatedBy());
        $this->assertSame([$admin], $user->roles());
    }

    /**
     * @test
     */
    public function it_removes_role()
    {
        $command = new RemoveUserRole();
        $command->userId = 'ce08c4e8-d9eb-435b-9eab-edc252b450e1';
        $command->editorId = '02158a54-0510-11e8-a654-005056806fb2';
        $command->role = 'admin';

        $user = new JohnDoeUser();
        $editor = new JaneDoeUser();
        $admin = new AdminRole();

        $user->assignRole($admin);

        $this->users
            ->expects($this->exactly(2))
            ->method('byId')
            ->withConsecutive(
                ['02158a54-0510-11e8-a654-005056806fb2'],
                ['ce08c4e8-d9eb-435b-9eab-edc252b450e1']
            )
            ->willReturnOnConsecutiveCalls($editor, $user)
        ;
        $this->roles
            ->expects($this->once())
            ->method('byCode')
            ->with('admin')
            ->willReturn($admin)
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

        $this->assertSame($dto, $this->service->removeRole($command));

        $this->assertSame($editor, $user->updatedBy());
        $this->assertSame([], $user->roles());
    }
}
