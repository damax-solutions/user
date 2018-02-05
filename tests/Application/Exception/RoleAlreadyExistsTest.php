<?php

declare(strict_types=1);

namespace Damax\User\Tests\Application\Exception;

use Damax\User\Application\Exception\RoleAlreadyExists;
use PHPUnit\Framework\TestCase;

class RoleAlreadyExistsTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_with_code()
    {
        $e = RoleAlreadyExists::withCode('admin');

        $this->assertEquals('Role with code "admin" already exists.', $e->getMessage());
    }
}
