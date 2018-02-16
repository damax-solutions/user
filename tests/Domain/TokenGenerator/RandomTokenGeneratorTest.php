<?php

declare(strict_types=1);

namespace Damax\User\Tests\Domain\TokenGenerator;

use Damax\User\Domain\TokenGenerator\RandomTokenGenerator;
use PHPUnit\Framework\TestCase;

class RandomTokenGeneratorTest extends TestCase
{
    /**
     * @test
     */
    public function it_generates_token()
    {
        $generator = new RandomTokenGenerator();

        $token1 = $generator->generateToken();
        $token2 = $generator->generateToken();

        $this->assertTrue(40 === strlen($token1));
        $this->assertTrue(40 === strlen($token2));
        $this->assertNotEquals($token1, $token2);
    }
}
