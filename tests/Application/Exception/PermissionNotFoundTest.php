<?php

declare(strict_types=1);

namespace Damax\User\Tests\Application\Exception;

use Damax\User\Application\Exception\PermissionNotFound;
use PHPUnit\Framework\TestCase;

class PermissionNotFoundTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_by_code()
    {
        $e = PermissionNotFound::byCode('admin_create');

        $this->assertEquals('Permission by code "admin_create" not found.', $e->getMessage());
    }
}
