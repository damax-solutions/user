<?php

declare(strict_types=1);

namespace Damax\User\Application\Command;

use Damax\User\Application\Dto\UserRegistrationDto;

class RegisterUser
{
    /**
     * @var UserRegistrationDto
     */
    public $user;

    /**
     * @var string|null
     */
    public $creatorId;
}
