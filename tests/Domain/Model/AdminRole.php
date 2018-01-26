<?php

declare(strict_types=1);

namespace Damax\User\Tests\Domain\Model;

use Damax\User\Domain\Model\Role;

class AdminRole extends Role
{
    public function __construct()
    {
        parent::__construct('admin', 'Admin', [
            'user_create',
            'user_edit',
            'user_delete',
        ]);
    }
}
