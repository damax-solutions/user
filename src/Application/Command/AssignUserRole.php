<?php

declare(strict_types=1);

namespace Damax\User\Application\Command;

class AssignUserRole
{
    /**
     * @var string
     */
    public $userId;

    /**
     * @var string
     */
    public $role;

    /**
     * @var string
     */
    public $editorId;
}
