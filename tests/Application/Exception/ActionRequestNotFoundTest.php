<?php

declare(strict_types=1);

namespace Damax\User\Tests\Application\Exception;

use Damax\User\Application\Exception\ActionRequestNotFound;
use PHPUnit\Framework\TestCase;

class ActionRequestNotFoundTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_by_token()
    {
        $e = ActionRequestNotFound::byToken('XYZ');

        $this->assertEquals('Action request by token "XYZ" not found.', $e->getMessage());
    }
}
