<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class PasswordValidator extends ConstraintValidator
{
    public function validate($password, Constraint $constraint)
    {
        if (!$constraint instanceof Password) {
            throw new UnexpectedTypeException($constraint, Password::class);
        }

        $patterns = [
            '/[a-z]/',
            '/[A-Z]/',
            '/[0-9]/',
            '/.{' . $constraint->minLength . ',}/',
        ];

        foreach ($patterns as $pattern) {
            if (!preg_match($pattern, (string) $password)) {
                $this->context
                    ->buildViolation($constraint->message)
                    ->setParameter('%min_length%', $constraint->minLength)
                    ->addViolation()
                ;
                break;
            }
        }
    }
}
