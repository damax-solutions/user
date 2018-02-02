<?php

declare(strict_types=1);

namespace Damax\User\Application\Dto;

class RoleBodyDto
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string[]
     */
    public $permissions = [];
}
