<?php

declare(strict_types=1);

namespace Damax\User\Application\Command;

use ArrayAccess;
use Damax\User\Application\AsArrayTrait;
use Damax\User\Application\Dto\NameDto;

class RegisterUser implements ArrayAccess
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
