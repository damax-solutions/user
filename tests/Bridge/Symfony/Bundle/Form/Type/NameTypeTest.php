<?php

declare(strict_types=1);

namespace Damax\User\Tests\Bridge\Symfony\Bundle\Form\Type;

use Damax\User\Application\Dto\NameDto;
use Damax\User\Bridge\Symfony\Bundle\Form\Type\NameType;
use Symfony\Component\Form\Test\FormIntegrationTestCase;

class NameTypeTest extends FormIntegrationTestCase
{
    /**
     * @test
     */
    public function it_submits_data()
    {
        $form = $this->factory->create(NameType::class);

        $form->submit([
            'firstName' => 'Dmitri',
            'lastName' => 'Lakachauskis',
        ]);

        /** @var NameDto $data */
        $data = $form->getData();

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());
        $this->assertInstanceOf(NameDto::class, $data);
        $this->assertEquals('damax-user', $form->getConfig()->getOption('translation_domain'));
        $this->assertEquals('Dmitri', $data->firstName);
        $this->assertEquals('Lakachauskis', $data->lastName);
        $this->assertNull($data->middleName);
    }
}
