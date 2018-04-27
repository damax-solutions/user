<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Validator\Constraints;

use Damax\User\Domain\Model\MobilePhone;
use Damax\User\Domain\Model\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueMobilePhoneValidator extends ConstraintValidator
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function validate($mobilePhone, Constraint $constraint)
    {
        if (!$constraint instanceof UniqueMobilePhone) {
            throw new UnexpectedTypeException($constraint, UniqueMobilePhone::class);
        }

        if ($this->userRepository->byMobilePhone(MobilePhone::fromString($mobilePhone))) {
            $this->context
                ->buildViolation($constraint->message)
                ->addViolation()
            ;
        }
    }
}
