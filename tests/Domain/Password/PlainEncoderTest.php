<?php

declare(strict_types=1);

namespace Damax\User\Tests\Domain\Password;

use Damax\User\Domain\Password\PlainEncoder;
use PHPUnit\Framework\TestCase;

class PlainEncoderTest extends TestCase
{
    /**
     * @var PlainEncoder
     */
    private $encoder;

    protected function setUp()
    {
        $this->encoder = new PlainEncoder('XYZ');
    }

    /**
     * @test
     */
    public function it_encodes_password()
    {
        $password = $this->encoder->encode('qwerty');

        $this->assertEquals('qwerty', $password->password());
        $this->assertEquals('XYZ', $password->salt());
    }

    /**
     * @test
     */
    public function it_produces_salt()
    {
        $this->assertEquals('XYZ', $this->encoder->produceSalt());
    }
}
