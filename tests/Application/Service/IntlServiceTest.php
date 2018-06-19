<?php

declare(strict_types=1);

namespace Damax\User\Tests\Application\Service;

use Damax\User\Application\Dto\Assembler;
use Damax\User\Application\Dto\LocaleDto;
use Damax\User\Application\Service\IntlService;
use Damax\User\Domain\NameFormatter\NameFormatter;
use Damax\User\InMemory\LocaleRepository;
use Damax\User\InMemory\TimezoneRepository;
use PHPUnit\Framework\TestCase;

class IntlServiceTest extends TestCase
{
    /**
     * @var IntlService
     */
    private $service;

    protected function setUp()
    {
        /** @var NameFormatter $nameFormatter */
        $nameFormatter = $this->createMock(NameFormatter::class);

        $this->service = new IntlService(
            new LocaleRepository(['en', 'ru']),
            new TimezoneRepository(['Europe/Riga', 'Europe/London']),
            new Assembler($nameFormatter)
        );
    }

    /**
     * @test
     */
    public function it_fetches_locales()
    {
        $locales = $this->service->fetchLocales();

        $this->assertCount(2, $locales);
        $this->assertContainsOnlyInstancesOf(LocaleDto::class, $locales);

        $this->assertEquals('en', $locales[0]->code);
        $this->assertEquals('ru', $locales[1]->code);
        $this->assertEquals('English', $locales[0]->name);
        $this->assertEquals('Russian', $locales[1]->name);
    }

    /**
     * @test
     */
    public function it_fetches_timezones()
    {
        $this->assertEquals(['Europe/Riga', 'Europe/London'], $this->service->fetchTimezones());
    }
}
