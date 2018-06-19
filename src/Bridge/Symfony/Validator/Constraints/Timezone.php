<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class Timezone extends Constraint
{
    /**
     * @var string
     */
    public $message = 'damax_user.timezone.invalid';
}
