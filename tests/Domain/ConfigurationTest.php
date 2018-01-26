<?php

declare(strict_types=1);

namespace Damax\User\Tests\Domain;

use Damax\User\Domain\Configuration;
use Damax\User\Domain\Model\Locale;
use Damax\User\Domain\Model\Timezone;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_configuration()
    {
        $configuration = new Configuration($tz = Timezone::fromId('Europe/Riga'), $locale = Locale::fromCode('ru'), false);

        $this->assertSame($tz, $configuration->defaultTimezone());
        $this->assertSame($locale, $configuration->defaultLocale());
        $this->assertFalse($configuration->invalidatePassword());
    }
}
