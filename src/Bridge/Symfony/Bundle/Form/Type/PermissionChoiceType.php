<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Bundle\Form\Type;

use Damax\User\Application\Dto\PermissionDto;
use Damax\User\Application\Service\PermissionService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PermissionChoiceType extends AbstractType
{
    private $service;

    public function __construct(PermissionService $service)
    {
        $this->service = $service;
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $choices = array_map(function (PermissionDto $choice): string {
            return $choice->code;
        }, $this->service->fetchAll());

        $resolver->setDefaults([
            'translation_domain' => 'damax-user',
            'choice_translation_domain' => false,
            'choices' => array_combine($choices, $choices),
        ]);
    }
}
