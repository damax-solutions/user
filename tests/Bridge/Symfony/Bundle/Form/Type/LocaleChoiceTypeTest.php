<?php

declare(strict_types=1);

namespace Damax\User\Tests\Bridge\Symfony\Bundle\Form\Type;

use Damax\User\Application\Dto\Assembler;
use Damax\User\Application\Dto\LocaleDto;
use Damax\User\Application\Service\IntlService;
use Damax\User\Bridge\Symfony\Bundle\Form\Type\LocaleChoiceType;
use Damax\User\Domain\Model\Locale;
use Damax\User\Domain\NameFormatter\NameFormatter;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

class LocaleChoiceTypeTest extends TypeTestCase
{
    /**
     * @var IntlService|MockObject
     */
    private $service;

    /**
     * @var LocaleDto[]
     */
    private $choices = [];

    protected function setUp()
    {
        $assembler = new Assembler($this->createMock(NameFormatter::class));

        $this->choices = [
            $assembler->toLocaleDto(Locale::fromCode('en')),
            $assembler->toLocaleDto(Locale::fromCode('ru')),
        ];

        $this->service = $this->createMock(IntlService::class);

        $this->service
            ->expects($this->once())
            ->method('fetchLocales')
            ->willReturn($this->choices)
        ;

        parent::setUp();
    }

    /**
     * @test
     */
    public function it_retrieves_locales()
    {
        $form = $this->factory->create(LocaleChoiceType::class);
        $conf = $form->getConfig();

        $this->assertFalse($conf->getOption('choice_translation_domain'));
        $this->assertEquals('label.choose_locale', $conf->getOption('placeholder'));
        $this->assertEquals('damax-user', $conf->getOption('translation_domain'));
        $this->assertEquals(['English' => 'en', 'Russian' => 'ru'], $conf->getOption('choices'));
    }

    /**
     * @test
     */
    public function it_submits_data()
    {
        $form = $this->factory->create(LocaleChoiceType::class);
        $form->submit('ru');

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());
        $this->assertSame('ru', $form->getData());
    }

    /**
     * @test
     */
    public function it_submits_invalid_data()
    {
        $form = $this->factory->create(LocaleChoiceType::class);
        $form->submit('invalid');

        $this->assertNull($form->getData());
    }

    protected function getExtensions(): array
    {
        $types = [
            new LocaleChoiceType($this->service),
        ];

        return [
            new PreloadedExtension($types, []),
        ];
    }
}
