<?php

declare(strict_types=1);

namespace Damax\User\Application\Dto;

class PasswordResetDto
{
    /**
     * @var string
     */
    public $token;

    /**
     * @var string
     */
    public $newPassword;
}
