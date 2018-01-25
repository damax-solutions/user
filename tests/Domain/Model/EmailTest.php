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
        $email1 = Email::fromString('dmitlaka@damax.solutions');
        $email2 = Email::fromString('maksdrib@damax.solutions');

        $this->assertFalse($email1->sameAs($email2));
        $this->assertTrue($email1->sameAs(Email::fromString('dmitlaka@damax.solutions')));
    }

    /**
     * @test
     */
    public function it_verifies_email()
    {
        $email = Email::fromString('dmitlaka@damax.solutions');
        $this->assertFalse($email->verified());

        $verified = $email->verify();
        $this->assertTrue($verified->verified());

        $this->assertNotSame($email, $verified);
    }
}
