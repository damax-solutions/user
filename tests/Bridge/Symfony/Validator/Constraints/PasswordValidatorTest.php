<?php

declare(strict_types=1);

namespace Damax\User\Tests\Bridge\Symfony\Validator\Constraints;

use Damax\User\Bridge\Symfony\Validator\Constraints\Password;
use Damax\User\Bridge\Symfony\Validator\Constraints\PasswordValidator;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class PasswordValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @test
     */
    public function it_throws_exception_on_unsupported_constraint()
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->expectExceptionMessage(sprintf('Expected argument of type "%s", "%s" given', Password::class, NotBlank::class));

        $this->validator->validate('qwerty', new NotBlank());
    }

    /**
     * @test
     *
     * @dataProvider provideCorrectPasswordData
     */
    public function it_validates_correct_password(string $password)
    {
        $this->validator->validate($password, new Password(['minLength' => 5]));

        $this->assertNoViolation();
    }

    /**
     * @test
     *
     * @dataProvider provideIncorrectPasswordData
     */
    public function it_validates_incorrect_password(string $password)
    {
        $this->validator->validate($password, new Password(['minLength' => 5]));

        $this
            ->buildViolation('damax_user.password.invalid')
            ->setParameter('%min_length%', 5)
            ->assertRaised()
        ;
    }

    public function provideCorrectPasswordData(): array
    {
        return [
            ['zQHN1'],
            ['1Aaaaa'],
            ['!_aA1'],
        ];
    }

    public function provideIncorrectPasswordData(): array
    {
        return [
            ['qwerty'],
            ['qwerty12'],
            ['qwe'],
        ];
    }

    protected function createValidator(): ConstraintValidatorInterface
    {
        return new PasswordValidator();
    }
}
