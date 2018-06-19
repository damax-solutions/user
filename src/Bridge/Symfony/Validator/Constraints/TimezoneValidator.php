<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Validator\Constraints;

use Damax\User\Domain\Model\TimezoneRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class TimezoneValidator extends ConstraintValidator
{
    private $timezones;

    public function __construct(TimezoneRepository $timezones)
    {
        $this->timezones = $timezones;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Timezone) {
            throw new UnexpectedTypeException($constraint, Timezone::class);
        }

        if (!$value) {
            return;
        }

        if (null === $this->timezones->byId($value)) {
            $this->context
                ->buildViolation($constraint->message)
                ->addViolation()
            ;
        }
    }
}
