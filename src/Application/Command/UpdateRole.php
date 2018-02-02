<?php

declare(strict_types=1);

namespace Damax\User\Application\Command;

use Damax\User\Application\Dto\RoleBodyDto;

class UpdateRole
{
    /**
     * @var string
     */
    public $code;

    /**
     * @var RoleBodyDto
     */
    public $role;
}
