<?php

declare(strict_types=1);

namespace Damax\User\Application\Command;

use Damax\User\Application\Dto\NameDto;

class UpdateUser extends UserCommand
{
    /**
     * @var NameDto
     */
    public $name;

    /**
     * @var string
     */
    public $timezone;

    /**
     * @var string
     */
    public $locale;
}
