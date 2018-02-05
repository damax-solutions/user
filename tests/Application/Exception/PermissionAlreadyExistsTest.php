<?php

declare(strict_types=1);

namespace Damax\User\Tests\Application\Exception;

use Damax\User\Application\Exception\PermissionAlreadyExists;
use PHPUnit\Framework\TestCase;

class PermissionAlreadyExistsTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_with_code()
    {
        $e = PermissionAlreadyExists::withCode('admin_create');

        $this->assertEquals('Permission with code "admin_create" already exists.', $e->getMessage());
    }
}
