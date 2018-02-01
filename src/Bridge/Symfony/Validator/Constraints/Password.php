<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class Password extends Constraint
{
    /**
     * @var string
     */
    public $message = 'damax_user.password.invalid';

    /**
     * @var int
     */
    public $minLength = 6;
}
