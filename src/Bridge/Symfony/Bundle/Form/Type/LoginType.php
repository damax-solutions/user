<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Bundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginType extends AbstractType
{
    public function getBlockPrefix(): string
    {
        return '';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('_username', EmailType::class, [
                'label' => 'security.label.email',
                'data' => $options['last_username'],
            ])
            ->add('_password', PasswordType::class, [
                'label' => 'security.label.password',
            ])
            ->add('_remember_me', CheckboxType::class, [
                'label' => 'security.label.remember_me',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'damax-user',
            'last_username' => null,
            'csrf_field_name' => '_csrf_token',
            'csrf_token_id' => 'authenticate',
        ]);
    }
}
