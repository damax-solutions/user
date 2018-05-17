<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Bundle\Form\Type;

use Damax\User\Application\Dto\UserInfoDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\LocaleType;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', NameType::class)
            ->add('timezone', TimezoneType::class, [
                'label' => 'label.timezone',
            ])
            ->add('locale', LocaleType::class, [
                'label' => 'label.locale',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserInfoDto::class,
            'translation_domain' => 'damax-user',
        ]);
    }
}
