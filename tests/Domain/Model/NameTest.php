<?php

declare(strict_types=1);

namespace Damax\User\Tests\Domain\Model;

use Damax\User\Domain\Model\Name;
use PHPUnit\Framework\TestCase;

class NameTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_empty_name()
    {
        $name = Name::fromArray([]);

        $this->assertNull($name->firstName());
        $this->assertNull($name->lastName());
        $this->assertNull($name->middleName());
    }

    /**
     * @test
     */
    public function it_creates_from_array()
    {
        $name = Name::fromArray([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'middle_name' => 'Bill',
        ]);

        $this->assertEquals('John', $name->firstName());
        $this->assertEquals('Doe', $name->lastName());
        $this->assertEquals('Bill', $name->middleName());
    }
}
