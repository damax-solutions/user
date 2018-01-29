<?php

declare(strict_types=1);

namespace Damax\User\Tests\Application\Dto;

use Damax\User\Application\Dto\Assembler;
use Damax\User\Application\Dto\NameDto;
use Damax\User\Domain\Model\LoginHistory;
use Damax\User\Domain\Model\Name;
use Damax\User\Tests\Domain\Model\JohnDoeUser;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class AssemblerTest extends TestCase
{
    /**
     * @var Assembler
     */
    private $assembler;

    protected function setUp()
    {
        $this->assembler = new Assembler();
    }

    /**
     * @test
     */
    public function it_converts_name_to_dto()
    {
        $name = Name::fromArray(['first_name' => 'John', 'last_name' => 'Doe']);

        $dto = $this->assembler->toNameDto($name);

        $this->assertEquals('John', $dto->firstName);
        $this->assertEquals('Doe', $dto->lastName);
        $this->assertNull($dto->middleName);
    }

    /**
     * @test
     */
    public function it_converts_user_login_to_dto()
    {
        $dto = $this->assembler->toUserLoginDto(new LoginHistory(
            Uuid::fromString('0f0519b2-1d9f-4e3f-9ad4-b0c1fc1e0ecc'),
            Uuid::fromString('ce08c4e8-d9eb-435b-9eab-edc252b450e1'),
            '192.168.99.100',
            'Chrome'
        ));

        $this->assertEquals('0f0519b2-1d9f-4e3f-9ad4-b0c1fc1e0ecc', $dto->id);
        $this->assertEquals('192.168.99.100', $dto->clientIp);
        $this->assertEquals('Chrome', $dto->userAgent);
    }

    /**
     * @test
     */
    public function it_converts_user_to_dto()
    {
        $user = new JohnDoeUser();
        $user->loggedInOn($now = new DateTimeImmutable());

        $dto = $this->assembler->toUserDto($user);

        $this->assertEquals('ce08c4e8-d9eb-435b-9eab-edc252b450e1', $dto->id);
        $this->assertEquals('john.doe@domain.abc', $dto->email);
        $this->assertEquals('+123', $dto->mobilePhone);
        $this->assertEquals('Europe/Riga', $dto->timezone);
        $this->assertEquals('ru', $dto->locale);
        $this->assertTrue($dto->enabled);
        $this->assertSame($dto->createdAt, $user->createdAt());
        $this->assertSame($dto->updatedAt, $user->updatedAt());
        $this->assertSame($now, $user->lastLoginAt());
        $this->assertInstanceOf(NameDto::class, $dto->name);
    }
}
