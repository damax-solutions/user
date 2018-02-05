<?php

declare(strict_types=1);

namespace Damax\User\Tests\Application\Service;

use Damax\User\Application\Exception\RoleNotFound;
use Damax\User\Application\Service\RoleServiceTrait;
use Damax\User\Domain\Model\Role;
use Damax\User\Domain\Model\RoleRepository;
use Damax\User\Tests\Domain\Model\AdminRole;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class RoleServiceTraitTest extends TestCase
{
    /**
     * @var RoleRepository|PHPUnit_Framework_MockObject_MockObject
     */
    private $roles;

    /**
     * @var RoleServiceTrait
     */
    private $service;

    protected function setUp()
    {
        $this->roles = $this->createMock(RoleRepository::class);
        $this->service = new class($this->roles) {
            use RoleServiceTrait;

            private $roles;

            public function __construct(RoleRepository $roles)
            {
                $this->roles = $roles;
            }

            public function fetchRole(string $code): Role
            {
                return $this->getRole($code);
            }
        };
    }

    /**
     * @test
     */
    public function it_fetches_role()
    {
        $role = new AdminRole();

        $this->roles
            ->expects($this->once())
            ->method('byCode')
            ->with('admin')
            ->willReturn($role)
        ;

        $this->assertSame($role, $this->service->fetchRole('admin'));
    }

    /**
     * @test
     */
    public function it_throws_exception_when_role_is_missing()
    {
        $this->expectException(RoleNotFound::class);
        $this->expectExceptionMessage('Role by code "admin" not found.');

        $this->service->fetchRole('admin');
    }
}
