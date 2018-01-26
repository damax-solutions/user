<?php

declare(strict_types=1);

namespace Damax\User\Tests\Domain;

use Damax\User\Domain\Configuration;
use Damax\User\Domain\Model\Locale;
use Damax\User\Domain\Model\Timezone;

class DefaultConfiguration extends Configuration
{
    public function __construct()
    {
        parent::__construct(Timezone::fromId('Europe/Riga'), Locale::fromCode('ru'), true);
    }
}
