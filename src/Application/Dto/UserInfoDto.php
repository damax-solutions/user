<?php

declare(strict_types=1);

namespace Damax\User\Application\Dto;

class UserInfoDto
{
    /**
     * @var NameDto
     */
    public $name;

    /**
     * @var string
     */
    public $timezone;

    /**
     * @var string
     */
    public $locale;

    public static function fromUserDto(UserDto $user): self
    {
        $dto = new self();

        $dto->name = clone $user->name;
        $dto->timezone = $user->timezone;
        $dto->locale = $user->locale;

        return $dto;
    }
}
