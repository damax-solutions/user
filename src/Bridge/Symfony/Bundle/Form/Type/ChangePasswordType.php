<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Bundle\Form\Type;

use Damax\User\Application\Dto\UserPasswordDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword', PasswordType::class, [
                'label' => 'password.label.current_password',
            ])
            ->add('newPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'password.label.new_password'],
                'second_options' => ['label' => 'password.label.repeat_new_password'],
                'invalid_message' => 'damax_user.password.mismatch',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserPasswordDto::class,
            'translation_domain' => 'damax-user',
        ]);
    }
}
