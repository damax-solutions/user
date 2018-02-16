<?php

declare(strict_types=1);

namespace Damax\User\Application\Command;

class ResetPassword
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
