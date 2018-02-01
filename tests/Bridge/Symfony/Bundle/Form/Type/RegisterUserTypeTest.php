<?php

declare(strict_types=1);

namespace Damax\User\Tests\Bridge\Symfony\Bundle\Form\Type;

use Damax\User\Application\Command\RegisterUser;
use Damax\User\Application\Dto\NameDto;
use Damax\User\Bridge\Symfony\Bundle\Form\Type\RegisterUserType;
use Symfony\Component\Form\Test\FormIntegrationTestCase;

class RegisterUserTypeTest extends FormIntegrationTestCase
{
    /**
     * @test
     */
    public function it_submits_data()
    {
        $form = $this->factory->create(RegisterUserType::class);

        $form->submit([
            'email' => 'john.doe@domain.abc',
            'mobilePhone' => '+37120000001',
            'password' => 'Qwerty12',
            'name' => [
                'firstName' => 'Dmitri',
                'lastName' => 'Lakachauskis',
            ],
        ]);

        /** @var RegisterUser $data */
        $data = $form->getData();

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());
        $this->assertInstanceOf(RegisterUser::class, $data);
        $this->assertEquals('damax-user', $form->getConfig()->getOption('translation_domain'));
        $this->assertEquals('john.doe@domain.abc', $data->email);
        $this->assertEquals('+37120000001', $data->mobilePhone);
        $this->assertEquals('Qwerty12', $data->password);
        $this->assertInstanceOf(NameDto::class, $data->name);
        $this->assertEquals('Dmitri', $data->name->firstName);
        $this->assertEquals('Lakachauskis', $data->name->lastName);
        $this->assertNull($data->name->middleName);
    }
}
