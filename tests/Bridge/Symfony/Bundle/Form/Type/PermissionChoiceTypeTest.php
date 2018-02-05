<?php

declare(strict_types=1);

namespace Damax\User\Tests\Bridge\Symfony\Bundle\Form\Type;

use Damax\User\Application\Dto\PermissionDto;
use Damax\User\Application\Service\PermissionService;
use Damax\User\Bridge\Symfony\Bundle\Form\Type\PermissionChoiceType;
use PHPUnit_Framework_MockObject_MockObject;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\FormIntegrationTestCase;

class PermissionChoiceTypeTest extends FormIntegrationTestCase
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
    public function it_create_choices()
    {
        $form = $this->factory->create(PermissionChoiceType::class);

        $config = $form->getConfig();

        $this->assertEquals('damax-user', $config->getOption('translation_domain'));
        $this->assertFalse($config->getOption('choice_translation_domain'));
        $this->assertSame(['one' => 'one', 'two' => 'two'], $config->getOption('choices'));

        /** @var ChoiceView[] $view */
        $view = $form->createView()->vars['choices'];

        $this->assertContainsOnlyInstancesOf(ChoiceView::class, $view);
        $this->assertCount(2, $view);
        $this->assertEquals('one', $view[0]->label);
        $this->assertEquals('one', $view[0]->value);
        $this->assertEquals('two', $view[1]->label);
        $this->assertEquals('two', $view[1]->value);
    }

    /**
     * @test
     */
    public function it_submits_data()
    {
        $form = $this->factory->create(PermissionChoiceType::class);

        $form->submit('two');

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());
        $this->assertSame('two', $form->getData());
    }

    /**
     * @test
     */
    public function it_submits_invalid_data()
    {
        $form = $this->factory->create(PermissionChoiceType::class);

        $form->submit('invalid');

        $this->assertNull($form->getData());
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
