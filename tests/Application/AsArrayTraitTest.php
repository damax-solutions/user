<?php

declare(strict_types=1);

namespace Damax\User\Tests\Application;

use BadMethodCallException;
use Damax\User\Application\Command\RegisterUser;
use Damax\User\Application\Dto\NameDto;
use PHPUnit\Framework\TestCase;

class AsArrayTraitTest extends TestCase
{
    /**
     * @test
     */
    public function it_accesses_properties()
    {
        $command = new RegisterUser();
        $command->email = 'john.doe@domain.abc';
        $command->mobilePhone = '+123';

        $command->name = new NameDto();
        $command->name->firstName = 'John';
        $command->name->lastName = 'Doe';

        $this->assertTrue(isset($command['email']));
        $this->assertTrue(isset($command['mobile_phone']));
        $this->assertFalse(isset($command['password']));

        $this->assertEquals('john.doe@domain.abc', $command['email']);
        $this->assertEquals('+123', $command['mobile_phone']);

        // Internal prop as array.
        $this->assertEquals('John', $command['name']['first_name']);
        $this->assertEquals('Doe', $command['name']['last_name']);
        $this->assertFalse(isset($command['name']['middle_name']));
    }

    /**
     * @test
     */
    public function it_throws_exception_when_mutating_property()
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('Method "Damax\User\Application\AsArrayTrait::offsetSet" not implemented.');

        $command = new RegisterUser();

        $command['mobile_phone'] = '+123';
    }

    /**
     * @test
     */
    public function it_throws_exception_when_deleting_property()
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('Method "Damax\User\Application\AsArrayTrait::offsetUnset" not implemented.');

        $command = new RegisterUser();

        unset($command['mobile_phone']);
    }
}
