<?php

declare(strict_types=1);

namespace Damax\User\Application\Dto;

use ArrayAccess;
use Damax\Common\Application\AsArrayTrait;

class UserRegistrationDto implements ArrayAccess
{
    use AsArrayTrait;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $mobilePhone;

    /**
     * @var string
     */
    public $password;

    /**
     * @var NameDto
     */
    public $name;
}
