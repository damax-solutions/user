<?php

declare(strict_types=1);

namespace Damax\User\Application\Command;

class ChangePassword
{
    /**
     * @var string
     */
    public $userId;

    /**
     * @var string
     */
    public $newPassword;
}
