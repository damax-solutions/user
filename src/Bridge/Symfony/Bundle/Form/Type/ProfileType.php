<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Bundle\Form\Type;

use Damax\User\Application\Dto\UserInfoDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', NameType::class)
            ->add('locale', LocaleChoiceType::class, [
                'label' => 'label.locale',
            ])
            ->add('timezone', TimezoneChoiceType::class, [
                'label' => 'label.timezone',
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
