<?php

declare(strict_types=1);

namespace Damax\User\Application\Service;

use Damax\User\Application\Dto\Assembler;
use Damax\User\Application\Dto\LocaleDto;
use Damax\User\Domain\Model\LocaleRepository;
use Damax\User\Domain\Model\TimezoneRepository;

class IntlService
{
    private $locales;
    private $timezones;
    private $assembler;

    public function __construct(LocaleRepository $locales, TimezoneRepository $timezones, Assembler $assembler)
    {
        $this->locales = $locales;
        $this->timezones = $timezones;
        $this->assembler = $assembler;
    }

    /**
     * @return LocaleDto[]
     */
    public function fetchLocales(): array
    {
        return array_map([$this->assembler, 'toLocaleDto'], $this->locales->all());
    }

    /**
     * @return string[]
     */
    public function fetchTimezones(): array
    {
        return array_map('strval', $this->timezones->all());
    }
}
