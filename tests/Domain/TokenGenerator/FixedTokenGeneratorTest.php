<?php

declare(strict_types=1);

namespace Damax\User\Tests\Domain\TokenGenerator;

use Damax\User\Domain\TokenGenerator\FixedTokenGenerator;
use PHPUnit\Framework\TestCase;

class FixedTokenGeneratorTest extends TestCase
{
    /**
     * @test
     */
    public function it_generates_token()
    {
        $this->assertEquals('ABC', (new FixedTokenGenerator('ABC'))->generateToken());
    }
}
