<?php

declare(strict_types=1);

namespace Damax\User\Application\Dto;

use ArrayAccess;
use Damax\User\Application\AsArrayTrait;

class RoleDto extends RoleBodyDto implements ArrayAccess
{
    use AsArrayTrait;

    /**
     * @var string
     */
    public $code;
}
