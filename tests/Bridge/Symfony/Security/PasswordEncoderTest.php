<?php

declare(strict_types=1);

namespace Damax\User\Tests\Bridge\Symfony\Security;

use Damax\User\Bridge\Symfony\Security\PasswordEncoder;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class PasswordEncoderTest extends TestCase
{
    /**
     * @var PasswordEncoderInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private $securityEncoder;

    /**
     * @var PasswordEncoder
     */
    private $encoder;

    protected function setUp()
    {
        $this->securityEncoder = $this->createMock(PasswordEncoderInterface::class);
        $this->encoder = new PasswordEncoder($this->securityEncoder);
    }

    /**
     * @test
     */
    public function it_produces_salt()
    {
        $this->assertNotEquals($this->encoder->produceSalt(), $this->encoder->produceSalt());
        $this->assertLessThan(40, strlen($this->encoder->produceSalt()));
        $this->assertGreaterThan(16, strlen($this->encoder->produceSalt()));
    }

    /**
     * @test
     */
    public function it_encodes_password()
    {
        $producedSalt = null;

        $this->securityEncoder
            ->expects($this->once())
            ->method('encodePassword')
            ->willReturnCallback(function (string $password, string $salt) use (&$producedSalt): string {
                $producedSalt = $salt;

                return $password . '||' . $salt;
            })
        ;

        $password = $this->encoder->encode('qwerty');

        $this->assertNotNull($producedSalt);
        $this->assertEquals('qwerty||' . $producedSalt, $password->password());
        $this->assertEquals($producedSalt, $password->salt());
    }
}
