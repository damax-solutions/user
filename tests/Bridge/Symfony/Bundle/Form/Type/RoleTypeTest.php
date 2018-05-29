<?php

declare(strict_types=1);

namespace Damax\User\Tests\Bridge\Symfony\Bundle\Form\Type;

use Damax\User\Application\Dto\PermissionDto;
use Damax\User\Application\Dto\RoleDto;
use Damax\User\Application\Dto\RoleInfoDto;
use Damax\User\Application\Service\PermissionService;
use Damax\User\Bridge\Symfony\Bundle\Form\Type\PermissionChoiceType;
use Damax\User\Bridge\Symfony\Bundle\Form\Type\RoleType;
use PHPUnit_Framework_MockObject_MockObject;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\FormIntegrationTestCase;

class RoleTypeTest extends FormIntegrationTestCase
{
    /**
     * @var PermissionService|PHPUnit_Framework_MockObject_MockObject
     */
    private $service;

    protected function setUp()
    {
        $this->service = $this->createMock(PermissionService::class);

        $one = new PermissionDto();
        $one->code = 'one';
        $one->category = 'ABC';

        $two = new PermissionDto();
        $two->code = 'two';
        $two->category = 'ABC';

        $this->service->method('fetchAll')->willReturn([$one, $two]);

        parent::setUp();
    }

    /**
     * @test
     */
    public function it_submits_full_data()
    {
        $form = $this->factory->create(RoleType::class);

        $form->submit([
            'code' => 'admin',
            'name' => 'Admin',
            'permissions' => ['one', 'two'],
        ]);

        /** @var RoleDto $data */
        $data = $form->getData();

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());
        $this->assertInstanceOf(RoleDto::class, $data);

        $this->assertEquals('admin', $data->code);
        $this->assertEquals('Admin', $data->name);
        $this->assertEquals(['one', 'two'], $data->permissions);
    }

    /**
     * @test
     */
    public function it_submits_data()
    {
        $form = $this->factory->create(RoleType::class, null, ['full' => false]);

        $form->submit([
            'name' => 'Admin',
            'permissions' => ['one', 'two'],
        ]);

        /** @var RoleInfoDto $data */
        $data = $form->getData();

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());
        $this->assertInstanceOf(RoleInfoDto::class, $data);

        $this->assertEquals('Admin', $data->name);
        $this->assertEquals(['one', 'two'], $data->permissions);
    }

    protected function getExtensions(): array
    {
        $types = [
            new PermissionChoiceType($this->service),
        ];

        return [
            new PreloadedExtension($types, []),
        ];
    }
}
