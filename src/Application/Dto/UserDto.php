<?php

declare(strict_types=1);

namespace Damax\User\Application\Dto;

use DateTimeInterface;

class UserDto
{
    /**
     * @var string
     */
    public $id;

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
    public $timezone;

    /**
     * @var string
     */
    public $locale;

    /**
     * @var DateTimeInterface
     */
    public $createdAt;

    /**
     * @var DateTimeInterface
     */
    public $updatedAt;

    /**
     * @var bool
     */
    public $enabled;

    /**
     * @var DateTimeInterface
     */
    public $lastLoginAt;
}
