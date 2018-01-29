<?php

declare(strict_types=1);

namespace Damax\User\Tests\Domain\Model;

use Damax\User\Domain\Model\Email;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    /**
     * @test
     */
    public function it_compares_emails()
    {
        $email1 = Email::fromString('john.doe@domain.abc');
        $email2 = Email::fromString('janedoe@domain.abc');

        $this->assertFalse($email1->sameAs($email2));
        $this->assertTrue($email1->sameAs(Email::fromString('john.doe@domain.abc')));
    }

    /**
     * @test
     */
    public function it_verifies_email()
    {
        $email = Email::fromString('john.doe@domain.abc');
        $this->assertFalse($email->verified());

        $verified = $email->verify();
        $this->assertTrue($verified->verified());

        $this->assertNotSame($email, $verified);
    }

    /**
     * @test
     */
    public function it_creates_from_string()
    {
        $email = Email::fromString('john.doe@domain.abc');

        $this->assertEquals('john.doe@domain.abc', $email->email());
        $this->assertEquals('john.doe@domain.abc', (string) $email);
    }
}
