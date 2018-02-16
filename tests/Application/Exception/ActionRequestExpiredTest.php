<?php

declare(strict_types=1);

namespace Damax\User\Tests\Application\Exception;

use Damax\User\Application\Exception\ActionRequestExpired;
use PHPUnit\Framework\TestCase;

class ActionRequestExpiredTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_with_token()
    {
        $e = ActionRequestExpired::withToken('XYZ');

        $this->assertEquals('Action request with token "XYZ" is expired.', $e->getMessage());
    }
}
