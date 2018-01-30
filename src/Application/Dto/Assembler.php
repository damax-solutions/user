<?php

declare(strict_types=1);

namespace Damax\User\Application\Dto;

use Damax\User\Domain\Model\LoginHistory;
use Damax\User\Domain\Model\Name;
use Damax\User\Domain\Model\User;

class Assembler
{
    public function toNameDto(Name $name): NameDto
    {
        $dto = new NameDto();

        $dto->firstName = $name->firstName();
        $dto->lastName = $name->lastName();
        $dto->middleName = $name->middleName();

        return $dto;
    }

    public function toUserLoginDto(LoginHistory $loginHistory): UserLoginDto
    {
        $dto = new UserLoginDto();

        $dto->id = (string) $loginHistory->id();
        $dto->username = $loginHistory->username();
        $dto->clientIp = $loginHistory->clientIp();
        $dto->serverIp = $loginHistory->serverIp();
        $dto->userAgent = $loginHistory->userAgent();
        $dto->createdAt = $loginHistory->createdAt();

        return $dto;
    }

    public function toUserDto(User $user): UserDto
    {
        $dto = new UserDto();

        $dto->id = (string) $user->id();
        $dto->email = (string) $user->email();
        $dto->mobilePhone = (string) $user->mobilePhone();
        $dto->timezone = (string) $user->timezone();
        $dto->locale = (string) $user->locale();
        $dto->createdAt = $user->createdAt();
        $dto->updatedAt = $user->updatedAt();
        $dto->enabled = $user->enabled();
        $dto->lastLoginAt = $user->lastLoginAt();
        $dto->name = $this->toNameDto($user->name());

        return $dto;
    }
}
