<?php

declare(strict_types=1);

namespace Damax\User\Application\Dto;

use ArrayAccess;
use Damax\User\Application\AsArrayTrait;

class PermissionDto implements ArrayAccess
{
    use AsArrayTrait;

    /**
     * @var string
     */
    public $code;

    /**
     * @var string
     */
    public $category;

    /**
     * @var string|null
     */
    public $description;
}
