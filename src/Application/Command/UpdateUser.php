<?php

declare(strict_types=1);

namespace Damax\User\Application\Command;

use Damax\User\Application\Dto\NameDto;
use Damax\User\Application\Dto\UserDto;

class UpdateUser extends UserCommand
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

        $dto->userId = $user->id;
        $dto->name = clone $user->name;
        $dto->timezone = $user->timezone;
        $dto->locale = $user->locale;

        return $dto;
    }
}
