<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Bundle\Form\Type;

use Damax\User\Application\Dto\LocaleDto;
use Damax\User\Application\Service\IntlService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocaleChoiceType extends AbstractType
{
    private $service;

    public function __construct(IntlService $service)
    {
        $this->service = $service;
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $label = function (LocaleDto $dto): string {
            return $dto->name;
        };

        $value = function ($value): ?string {
            return $value instanceof LocaleDto ? $value->code : $value;
        };

        $resolver->setDefaults([
            'placeholder' => 'label.choose_locale',
            'translation_domain' => 'damax-user',
            'choices' => $this->service->fetchLocales(),
            'choice_translation_domain' => false,
            'choice_label' => $label,
            'choice_value' => $value,
        ]);
    }
}
