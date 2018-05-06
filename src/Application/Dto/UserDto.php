<?php

declare(strict_types=1);

namespace Damax\User\Application\Dto;

use DateTime;

class UserDto
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string[]
     */
    public $roles = [];

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $mobilePhone;

    /**
     * @var NameDto
     */
    public $name;

    /**
     * @var string
     */
    public $fullName;

    /**
     * @var string
     */
    public $timezone;

    /**
     * @var string
     */
    public $locale;

    /**
     * @var DateTime
     */
    public $createdAt;

    /**
     * @var DateTime
     */
    public $updatedAt;

    /**
     * @var bool
     */
    public $enabled;

    /**
     * @var DateTime
     */
    public $lastLoginAt;
}
