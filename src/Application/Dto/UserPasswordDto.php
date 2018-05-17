<?php

declare(strict_types=1);

namespace Damax\User\Application\Dto;

class UserPasswordDto
{
    /**
     * @var string
     */
    public $oldPassword;

    /**
     * @var string
     */
    public $newPassword;
}
