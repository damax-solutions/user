<?php

declare(strict_types=1);

namespace Damax\User\Tests\Bridge\Symfony\Bundle\Form\Type;

use Damax\User\Application\Service\IntlService;
use Damax\User\Bridge\Symfony\Bundle\Form\Type\TimezoneChoiceType;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

class TimezoneChoiceTypeTest extends TypeTestCase
{
    /**
     * @var IntlService|MockObject
     */
    private $service;

    protected function setUp()
    {
        $this->service = $this->createMock(IntlService::class);

        $this->service
            ->expects($this->once())
            ->method('fetchTimezones')
            ->willReturn(['Europe/Riga', 'Europe/London'])
        ;

        parent::setUp();
    }

    /**
     * @test
     */
    public function it_retrieves_timezones()
    {
        $form = $this->factory->create(TimezoneChoiceType::class);
        $conf = $form->getConfig();

        $this->assertFalse($conf->getOption('choice_translation_domain'));
        $this->assertEquals('label.choose_timezone', $conf->getOption('placeholder'));
        $this->assertEquals('damax-user', $conf->getOption('translation_domain'));
        $this->assertEquals(['Europe/Riga' => 'Europe/Riga', 'Europe/London' => 'Europe/London'], $conf->getOption('choices'));
    }

    /**
     * @test
     */
    public function it_submits_data()
    {
        $form = $this->factory->create(TimezoneChoiceType::class);
        $form->submit('Europe/Riga');

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());
        $this->assertEquals('Europe/Riga', $form->getData());
    }

    /**
     * @test
     */
    public function it_submits_invalid_data()
    {
        $form = $this->factory->create(TimezoneChoiceType::class);
        $form->submit('invalid');

        $this->assertNull($form->getData());
    }

    protected function getExtensions(): array
    {
        $types = [
            new TimezoneChoiceType($this->service),
        ];

        return [
            new PreloadedExtension($types, []),
        ];
    }
}
