<?php

declare(strict_types=1);

namespace Damax\User\Tests\Bridge\Symfony\Bundle\Form\Type;

use Damax\User\Application\Dto\PermissionDto;
use Damax\User\Bridge\Symfony\Bundle\Form\Type\PermissionType;
use Symfony\Component\Form\Test\FormIntegrationTestCase;

class PermissionTypeTest extends FormIntegrationTestCase
{
    /**
     * @test
     */
    public function it_submits_data()
    {
        $form = $this->factory->create(PermissionType::class);

        $form->submit([
            'code' => 'user_create',
            'category' => 'User',
            'description' => 'Create user functionality.',
        ]);

        /** @var PermissionDto $data */
        $data = $form->getData();

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());
        $this->assertInstanceOf(PermissionDto::class, $data);
        $this->assertEquals('damax-user', $form->getConfig()->getOption('translation_domain'));
        $this->assertEquals('user_create', $data->code);
        $this->assertEquals('User', $data->category);
        $this->assertEquals('Create user functionality.', $data->description);
    }
}
