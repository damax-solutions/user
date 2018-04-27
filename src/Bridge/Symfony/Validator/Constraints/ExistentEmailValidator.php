<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Validator\Constraints;

use Damax\User\Domain\Model\Email;
use Damax\User\Domain\Model\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ExistentEmailValidator extends ConstraintValidator
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function validate($email, Constraint $constraint)
    {
        if (!$constraint instanceof ExistentEmail) {
            throw new UnexpectedTypeException($constraint, ExistentEmail::class);
        }

        if (null === $this->userRepository->byEmail(Email::fromString($email))) {
            $this->context
                ->buildViolation($constraint->message)
                ->addViolation()
            ;
        }
    }
}
