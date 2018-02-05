<?php

declare(strict_types=1);

namespace Damax\User\Tests\Application\Exception;

use Damax\User\Application\Exception\UserAlreadyExists;
use PHPUnit\Framework\TestCase;

class UserAlreadyExistsTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_with_email()
    {
        $e = UserAlreadyExists::withEmail('john.doe@domain.abc');

        $this->assertEquals('User with email "john.doe@domain.abc" already exists.', $e->getMessage());
    }

    /**
     * @test
     */
    public function it_creates_with_mobile_phone()
    {
        $e = UserAlreadyExists::withMobilePhone('123');

        $this->assertEquals('User with mobile phone "123" already exists.', $e->getMessage());
    }
}
