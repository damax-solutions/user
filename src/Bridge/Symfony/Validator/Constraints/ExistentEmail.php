<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class ExistentEmail extends Constraint
{
    /**
     * @var string
     */
    public $message = 'damax_user.email.not_found';
}
