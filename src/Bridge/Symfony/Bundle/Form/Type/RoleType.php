<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Bundle\Form\Type;

use Damax\User\Application\Dto\RoleBodyDto;
use Damax\User\Application\Dto\RoleDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['full']) {
            $builder->add('code', TextType::class, [
                'label' => 'role.label.code',
            ]);
        }

        $builder
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
            'full' => true,
            'translation_domain' => 'damax-user',
            'data_class' => function (Options $options) {
                return $options['full'] ? RoleDto::class : RoleBodyDto::class;
            },
        ]);
    }
}
