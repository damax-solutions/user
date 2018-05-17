<?php

declare(strict_types=1);

namespace Damax\User\Application\Command;

use Damax\User\Application\Dto\UserInfoDto;

class UpdateUser
{
    /**
     * @var string
     */
    public $userId;

    /**
     * @var UserInfoDto
     */
    public $info;
}
