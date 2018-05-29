<?php

declare(strict_types=1);

namespace Damax\User\Tests\Application\Service;

use Damax\User\Application\Command\CreateRole;
use Damax\User\Application\Command\UpdateRole;
use Damax\User\Application\Dto\Assembler;
use Damax\User\Application\Dto\RoleDto;
use Damax\User\Application\Dto\RoleInfoDto;
use Damax\User\Application\Exception\RoleAlreadyExists;
use Damax\User\Application\Service\RoleService;
use Damax\User\Domain\Model\RoleFactory;
use Damax\User\Domain\Model\RoleRepository;
use Damax\User\Tests\Domain\Model\AdminRole;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class RoleServiceTest extends TestCase
{
    /**
     * @var RoleRepository|PHPUnit_Framework_MockObject_MockObject
     */
    private $roles;

    /**
     * @var RoleFactory|PHPUnit_Framework_MockObject_MockObject
     */
    private $factory;

    /**
     * @var Assembler|PHPUnit_Framework_MockObject_MockObject
     */
    private $assembler;

    /**
     * @var RoleService
     */
    private $service;

    protected function setUp()
    {
        $this->roles = $this->createMock(RoleRepository::class);
        $this->factory = $this->createMock(RoleFactory::class);
        $this->assembler = $this->createMock(Assembler::class);
        $this->service = new RoleService($this->roles, $this->factory, $this->assembler);
    }

    /**
     * @test
     */
    public function it_fetches_role()
    {
        $this->roles
            ->expects($this->once())
            ->method('byCode')
            ->with('admin')
            ->willReturn($role = new AdminRole())
        ;
        $this->assembler
            ->expects($this->once())
            ->method('toRoleDto')
            ->with($this->identicalTo($role))
            ->willReturn($dto = new RoleDto())
        ;

        $this->assertSame($dto, $this->service->fetch('admin'));
    }

    /**
     * @test
     */
    public function it_fetches_all()
    {
        $one = new AdminRole();
        $two = new AdminRole();

        $this->roles
            ->expects($this->once())
            ->method('all')
            ->willReturn([$one, $two])
        ;
        $this->assembler
            ->expects($this->exactly(2))
            ->method('toRoleDto')
            ->withConsecutive(
                [$this->identicalTo($one)],
                [$this->identicalTo($two)]
            )
            ->willReturnOnConsecutiveCalls(
                $dto1 = new RoleDto(),
                $dto2 = new RoleDto()
            )
        ;

        $this->assertSame([$dto1, $dto2], $this->service->fetchAll());
    }

    /**
     * @test
     */
    public function it_deletes_role()
    {
        $this->roles
            ->expects($this->once())
            ->method('byCode')
            ->with('admin')
            ->willReturn($admin = new AdminRole())
        ;
        $this->roles
            ->expects($this->once())
            ->method('remove')
            ->with($this->identicalTo($admin))
        ;
        $this->assembler
            ->expects($this->once())
            ->method('toRoleDto')
            ->with($this->identicalTo($admin))
            ->willReturn($dto = new RoleDto())
        ;

        $this->assertSame($dto, $this->service->delete('admin'));
    }

    /**
     * @test
     */
    public function it_updates_role()
    {
        $command = new UpdateRole();
        $command->code = 'admin';
        $command->info = new RoleInfoDto();
        $command->info->name = 'New admin';
        $command->info->permissions = ['foo', 'bar'];

        $this->roles
            ->expects($this->once())
            ->method('byCode')
            ->with('admin')
            ->willReturn($admin = new AdminRole())
        ;
        $this->roles
            ->expects($this->once())
            ->method('save')
            ->with($this->identicalTo($admin))
        ;
        $this->assembler
            ->expects($this->once())
            ->method('toRoleDto')
            ->with($this->identicalTo($admin))
            ->willReturn($dto = new RoleDto())
        ;

        $this->assertSame($dto, $this->service->update($command));

        $this->assertEquals('New admin', $admin->name());
        $this->assertEquals(['foo', 'bar'], $admin->permissions());
    }

    /**
     * @test
     */
    public function it_creates_role()
    {
        $command = new CreateRole();
        $command->role = new RoleDto();
        $command->role->code = 'admin';
        $command->role->name = 'Admin';
        $command->role->permissions = ['foo', 'bar'];

        $this->factory
            ->expects($this->once())
            ->method('create')
            ->with($this->identicalTo($command->role))
            ->willReturn($role = new AdminRole())
        ;
        $this->roles
            ->expects($this->once())
            ->method('save')
            ->with($this->identicalTo($role))
        ;
        $this->assembler
            ->expects($this->once())
            ->method('toRoleDto')
            ->with($this->identicalTo($role))
            ->willReturn($dto = new RoleDto())
        ;

        $this->assertSame($dto, $this->service->create($command));
    }

    /**
     * @test
     */
    public function it_throws_exception_when_creating_role_with_existing_code()
    {
        $command = new CreateRole();
        $command->role = new RoleDto();
        $command->role->code = 'admin';
        $command->role->name = 'Admin';

        $this->roles
            ->expects($this->once())
            ->method('byCode')
            ->with('admin')
            ->willReturn(new AdminRole())
        ;
        $this->roles
            ->expects($this->never())
            ->method('save')
        ;

        $this->expectException(RoleAlreadyExists::class);
        $this->expectExceptionMessage('Role with code "admin" already exists.');

        $this->service->create($command);
    }
}
