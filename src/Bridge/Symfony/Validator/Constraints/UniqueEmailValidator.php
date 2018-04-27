<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Validator\Constraints;

use Damax\User\Domain\Model\Email;
use Damax\User\Domain\Model\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueEmailValidator extends ConstraintValidator
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function validate($email, Constraint $constraint)
    {
        if (!$constraint instanceof UniqueEmail) {
            throw new UnexpectedTypeException($constraint, UniqueEmail::class);
        }

        if (!$email) {
            return;
        }

        if ($this->userRepository->byEmail(Email::fromString($email))) {
            $this->context
                ->buildViolation($constraint->message)
                ->addViolation()
            ;
        }
    }
}
