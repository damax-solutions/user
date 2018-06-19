<?php

declare(strict_types=1);

namespace Damax\User\Tests\Application\Dto;

use Damax\User\Application\Dto\NameDto;
use Damax\User\Application\Dto\UserDto;
use Damax\User\Application\Dto\UserInfoDto;
use PHPUnit\Framework\TestCase;

class UserInfoDtoTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_from_user_dto()
    {
        $userDto = new UserDto();
        $userDto->locale = 'ru';
        $userDto->timezone = 'Europe/Riga';
        $userDto->name = new NameDto();
        $userDto->name->firstName = 'John';
        $userDto->name->lastName = 'Doe';

        $dto = UserInfoDto::fromUserDto($userDto);

        $this->assertEquals('ru', $dto->locale);
        $this->assertEquals('Europe/Riga', $dto->timezone);
        $this->assertEquals('John', $dto->name->firstName);
        $this->assertEquals('Doe', $dto->name->lastName);
        $this->assertNull($dto->name->middleName);
    }
}
