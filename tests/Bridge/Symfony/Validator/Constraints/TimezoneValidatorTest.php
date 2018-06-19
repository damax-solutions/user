<?php

declare(strict_types=1);

namespace Damax\User\Tests\Bridge\Symfony\Validator\Constraints;

use Damax\User\Bridge\Symfony\Validator\Constraints\Timezone;
use Damax\User\Bridge\Symfony\Validator\Constraints\TimezoneValidator;
use Damax\User\Domain\Model\TimezoneRepository;
use Damax\User\InMemory\TimezoneRepository as InMemoryTimezoneRepository;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class TimezoneValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @var TimezoneRepository
     */
    private $timezones;

    protected function setUp()
    {
        $this->timezones = new InMemoryTimezoneRepository(['Europe/Riga', 'Europe/London']);

        parent::setUp();
    }

    /**
     * @test
     */
    public function it_throws_exception_on_unsupported_constraint()
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->expectExceptionMessage(sprintf('Expected argument of type "%s", "%s" given', Timezone::class, NotBlank::class));

        $this->validator->validate('Europe/Riga', new NotBlank());
    }

    /**
     * @test
     */
    public function it_has_no_violations_on_empty_value()
    {
        $this->validator->validate('', new Timezone());

        $this->assertNoViolation();
    }

    /**
     * @test
     */
    public function it_validates_correct_timezone()
    {
        $this->validator->validate('Europe/Riga', new Timezone());

        $this->assertNoViolation();
    }

    /**
     * @test
     */
    public function it_validates_incorrect_timezone()
    {
        $this->validator->validate('Europe/Helsinki', new Timezone());

        $this
            ->buildViolation('damax_user.timezone.invalid')
            ->assertRaised()
        ;
    }

    protected function createValidator(): ConstraintValidatorInterface
    {
        return new TimezoneValidator($this->timezones);
    }
}
