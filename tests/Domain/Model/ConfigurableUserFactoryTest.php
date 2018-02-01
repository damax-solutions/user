<?php

declare(strict_types=1);

namespace Damax\User\Tests\Domain\Model;

use Assert\InvalidArgumentException;
use Damax\User\Domain\Model\ConfigurableUserFactory;
use Damax\User\Domain\Model\UserRepository;
use Damax\User\Domain\Password\PlainEncoder;
use Damax\User\Tests\Domain\DefaultConfiguration;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Ramsey\Uuid\Uuid;

class ConfigurableUserFactoryTest extends TestCase
{
    /**
     * @var UserRepository|PHPUnit_Framework_MockObject_MockObject
     */
    private $users;

    /**
     * @var ConfigurableUserFactory
     */
    private $factory;

    protected function setUp()
    {
        $this->users = $this->createMock(UserRepository::class);
        $this->factory = new ConfigurableUserFactory($this->users, new PlainEncoder('XYZ'), new DefaultConfiguration());
    }

    /**
     * @test
     */
    public function it_creates_user()
    {
        $creator = new JaneDoeUser();

        $this->users
            ->method('nextId')
            ->willReturn(Uuid::fromString('ce08c4e8-d9eb-435b-9eab-edc252b450e1'))
        ;

        $user = $this->factory->create([
            'email' => 'john.doe@domain.abc',
            'mobile_phone' => '123',
            'password' => 'qwerty',
            'name' => [
                'first_name' => 'John',
                'last_name' => 'Doe',
            ],
        ], $creator);

        $this->assertEquals('john.doe@domain.abc', $user->email()->email());
        $this->assertEquals(123, $user->mobilePhone()->number());
        $this->assertEquals('Europe/Riga', $user->timezone()->id());
        $this->assertEquals('ru', $user->locale()->code());
        $this->assertTrue($user->enabled());
        $this->assertSame($creator, $user->createdBy());
        $this->assertSame($creator, $user->updatedBy());

        // Name
        $this->assertEquals('John', $user->name()->firstName());
        $this->assertEquals('Doe', $user->name()->lastName());
        $this->assertNull($user->name()->middleName());

        // Password
        $this->assertEquals('qwerty', $user->password()->password());
        $this->assertEquals('XYZ', $user->password()->salt());
        $this->assertTrue($user->password()->expired());
    }

    /**
     * @test
     */
    public function it_creates_user_with_valid_password()
    {
        $config = new class() extends DefaultConfiguration {
            public function invalidatePassword(): bool
            {
                return false;
            }
        };

        $factory = new ConfigurableUserFactory($this->users, new PlainEncoder('XYZ'), $config);

        $user = $factory->create([
            'email' => 'john.doe@domain.abc',
            'mobile_phone' => '123',
            'password' => 'qwerty',
            'name' => [],
        ]);

        $this->assertFalse($user->password()->expired());
    }

    /**
     * @test
     */
    public function it_throws_exception_when_creating_user_with_missing_data()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The element with key "password" was not found');

        $this->factory->create([
            'email' => 'john.doe@domain.abc',
            'mobile_phone' => '123',
            'password' => null,
        ]);
    }
}
