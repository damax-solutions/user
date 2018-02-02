<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Bundle\Form\Type;

use Damax\User\Application\Dto\RoleDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code', TextType::class, [
                'label' => 'role.label.code',
            ])
            ->add('name', TextType::class, [
                'label' => 'role.label.name',
            ])
            ->add('permissions', PermissionChoiceType::class, [
                'label' => 'role.label.permissions',
                'required' => false,
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RoleDto::class,
            'translation_domain' => 'damax-user',
        ]);
    }
}
