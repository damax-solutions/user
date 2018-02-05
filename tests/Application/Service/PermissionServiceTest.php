<?php

declare(strict_types=1);

namespace Damax\User\Tests\Application\Service;

use Damax\User\Application\Command\CreatePermission;
use Damax\User\Application\Dto\Assembler;
use Damax\User\Application\Dto\PermissionDto;
use Damax\User\Application\Exception\PermissionAlreadyExists;
use Damax\User\Application\Exception\PermissionNotFound;
use Damax\User\Application\Service\PermissionService;
use Damax\User\Domain\Model\Permission;
use Damax\User\Domain\Model\PermissionFactory;
use Damax\User\Domain\Model\PermissionRepository;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class PermissionServiceTest extends TestCase
{
    /**
     * @var PermissionRepository|PHPUnit_Framework_MockObject_MockObject
     */
    private $permissions;

    /**
     * @var PermissionFactory|PHPUnit_Framework_MockObject_MockObject
     */
    private $factory;

    /**
     * @var Assembler|PHPUnit_Framework_MockObject_MockObject
     */
    private $assembler;

    /**
     * @var PermissionService
     */
    private $service;

    protected function setUp()
    {
        $this->permissions = $this->createMock(PermissionRepository::class);
        $this->factory = $this->createMock(PermissionFactory::class);
        $this->assembler = $this->createMock(Assembler::class);
        $this->service = new PermissionService($this->permissions, $this->factory, $this->assembler);
    }

    /**
     * @test
     */
    public function it_fetches_all()
    {
        $one = new Permission('user_create', 'User');
        $two = new Permission('user_delete', 'User');

        $this->permissions
            ->expects($this->once())
            ->method('all')
            ->willReturn([$one, $two])
        ;
        $this->assembler
            ->expects($this->exactly(2))
            ->method('toPermissionDto')
            ->withConsecutive(
                [$this->identicalTo($one)],
                [$this->identicalTo($two)]
            )
            ->willReturnOnConsecutiveCalls(
                $dto1 = new PermissionDto(),
                $dto2 = new PermissionDto()
            )
        ;

        $this->assertSame([$dto1, $dto2], $this->service->fetchAll());
    }

    /**
     * @test
     */
    public function it_fetches_by_category()
    {
        $one = new Permission('user_create', 'User');
        $two = new Permission('user_delete', 'User');

        $this->permissions
            ->expects($this->once())
            ->method('byCategory')
            ->with('User')
            ->willReturn([$one, $two])
        ;
        $this->assembler
            ->expects($this->exactly(2))
            ->method('toPermissionDto')
            ->withConsecutive(
                [$this->identicalTo($one)],
                [$this->identicalTo($two)]
            )
            ->willReturnOnConsecutiveCalls(
                $dto1 = new PermissionDto(),
                $dto2 = new PermissionDto()
            )
        ;

        $this->assertSame([$dto1, $dto2], $this->service->fetchByCategory('User'));
    }

    /**
     * @test
     */
    public function it_deletes_permission()
    {
        $this->permissions
            ->expects($this->once())
            ->method('byCode')
            ->with('admin_create')
            ->willReturn($permission = new Permission('user_create', 'User'))
        ;
        $this->permissions
            ->expects($this->once())
            ->method('remove')
            ->with($this->identicalTo($permission))
        ;
        $this->assembler
            ->expects($this->once())
            ->method('toPermissionDto')
            ->with($this->identicalTo($permission))
            ->willReturn($dto = new PermissionDto())
        ;

        $this->assertSame($dto, $this->service->delete('admin_create'));
    }

    /**
     * @test
     */
    public function it_throws_exception_when_deleting_missing_permission()
    {
        $this->expectException(PermissionNotFound::class);
        $this->expectExceptionMessage('Permission by code "admin_create" not found.');

        $this->service->delete('admin_create');
    }

    /**
     * @test
     */
    public function it_creates_permission()
    {
        $command = new CreatePermission();
        $command->permission = new PermissionDto();
        $command->permission->code = 'user_create';
        $command->permission->category = 'User';

        $this->factory
            ->expects($this->once())
            ->method('create')
            ->with($this->identicalTo($command->permission))
            ->willReturn($permission = new Permission('user_create', 'User'))
        ;
        $this->permissions
            ->expects($this->once())
            ->method('save')
            ->with($this->identicalTo($permission))
        ;
        $this->assembler
            ->expects($this->once())
            ->method('toPermissionDto')
            ->with($this->identicalTo($permission))
            ->willReturn($dto = new PermissionDto())
        ;

        $this->assertSame($dto, $this->service->create($command));
    }

    /**
     * @test
     */
    public function it_throws_exception_when_creating_permission_with_existing_code()
    {
        $command = new CreatePermission();
        $command->permission = new PermissionDto();
        $command->permission->code = 'user_create';
        $command->permission->category = 'User';

        $this->permissions
            ->expects($this->once())
            ->method('byCode')
            ->with('user_create')
            ->willReturn(new Permission('user_create', 'User'))
        ;
        $this->permissions
            ->expects($this->never())
            ->method('save')
        ;

        $this->expectException(PermissionAlreadyExists::class);
        $this->expectExceptionMessage('Permission with code "user_create" already exists.');

        $this->service->create($command);
    }
}
