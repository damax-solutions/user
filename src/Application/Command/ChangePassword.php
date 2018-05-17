<?php

declare(strict_types=1);

namespace Damax\User\Application\Command;

use Damax\User\Application\Dto\UserPasswordDto;

class ChangePassword
{
    /**
     * @var string
     */
    public $userId;

    /**
     * @var UserPasswordDto
     */
    public $password;
}
