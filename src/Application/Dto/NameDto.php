<?php

declare(strict_types=1);

namespace Damax\User\Application\Dto;

use ArrayAccess;
use Damax\User\Application\AsArrayTrait;

class NameDto implements ArrayAccess
{
    use AsArrayTrait;

    /**
     * @var string
     */
    public $firstName;

    /**
     * @var string
     */
    public $lastName;

    /**
     * @var string
     */
    public $middleName;
}
