<?php

declare(strict_types=1);

namespace Damax\User\Tests\Bridge\Symfony\Validator\Constraints;

use Damax\User\Bridge\Symfony\Validator\Constraints\Locale;
use Damax\User\Bridge\Symfony\Validator\Constraints\LocaleValidator;
use Damax\User\Domain\Model\LocaleRepository;
use Damax\User\InMemory\LocaleRepository as InMemoryLocaleRepository;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class LocaleValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @var LocaleRepository
     */
    private $locales;

    protected function setUp()
    {
        $this->locales = new InMemoryLocaleRepository(['en', 'ru']);

        parent::setUp();
    }

    /**
     * @test
     */
    public function it_throws_exception_on_unsupported_constraint()
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->expectExceptionMessage(sprintf('Expected argument of type "%s", "%s" given', Locale::class, NotBlank::class));

        $this->validator->validate('ru', new NotBlank());
    }

    /**
     * @test
     */
    public function it_has_no_violations_on_empty_value()
    {
        $this->validator->validate('', new Locale());

        $this->assertNoViolation();
    }

    /**
     * @test
     */
    public function it_validates_correct_locale()
    {
        $this->validator->validate('ru', new Locale());

        $this->assertNoViolation();
    }

    /**
     * @test
     */
    public function it_validates_incorrect_locale()
    {
        $this->validator->validate('de', new Locale());

        $this
            ->buildViolation('damax_user.locale.invalid')
            ->assertRaised()
        ;
    }

    protected function createValidator(): ConstraintValidatorInterface
    {
        return new LocaleValidator($this->locales);
    }
}
