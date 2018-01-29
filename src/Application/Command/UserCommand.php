<?php

declare(strict_types=1);

namespace Damax\User\Application\Command;

abstract class UserCommand
{
    /**
     * @var string
     */
    public $userId;

    /**
     * @var string|null
     */
    public $editorId;
}
