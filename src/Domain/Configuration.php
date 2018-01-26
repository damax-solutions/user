<?php

declare(strict_types=1);

namespace Damax\User\Domain;

use Damax\User\Domain\Model\Locale;
use Damax\User\Domain\Model\Timezone;

class Configuration
{
    private $timezone;
    private $locale;
    private $invalidatePassword;

    public function __construct(Timezone $timezone, Locale $locale, bool $invalidatePassword)
    {
        $this->timezone = $timezone;
        $this->locale = $locale;
        $this->invalidatePassword = $invalidatePassword;
    }

    public function defaultTimezone(): Timezone
    {
        return $this->timezone;
    }

    public function defaultLocale(): Locale
    {
        return $this->locale;
    }

    public function invalidatePassword(): bool
    {
        return $this->invalidatePassword;
    }
}
