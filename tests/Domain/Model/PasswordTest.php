<?php

declare(strict_types=1);

namespace Damax\User\Tests\Domain\Model;

use Damax\User\Domain\Model\Password;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\PhpUnit\ClockMock;

/**
 * @group time-sensitive
 */
class PasswordTest extends TestCase
{
    public function setUp()
    {
        ClockMock::withClockMock(strtotime('2018-01-20 06:10:00'));
    }

    /**
     * @test
     */
    public function it_creates_valid_password_for_3_months()
    {
        $password = Password::valid3Months('qwerty', 'XYZ');

        $this->assertEquals('qwerty', $password->password());
        $this->assertEquals('XYZ', $password->salt());
        $this->assertFalse($password->expired());

        ClockMock::withClockMock(strtotime('2018-04-20 06:09:59'));
        $this->assertFalse($password->expired());

        ClockMock::withClockMock(strtotime('2018-04-20 06:10:00'));
        $this->assertTrue($password->expired());
    }

    /**
     * @test
     */
    public function it_creates_valid_password_for_6_months()
    {
        $password = Password::valid6Months('qwerty', 'XYZ');
        $this->assertFalse($password->expired());

        ClockMock::withClockMock(strtotime('2018-07-20 06:09:59'));
        $this->assertFalse($password->expired());

        ClockMock::withClockMock(strtotime('2018-07-20 06:10:00'));
        $this->assertTrue($password->expired());
    }

    /**
     * @test
     */
    public function it_invalidates_password()
    {
        $password = Password::valid6Months('qwerty', 'XYZ');
        $this->assertTrue($password->invalidate()->expired());
    }
}
