<?php

declare(strict_types=1);

namespace Damax\User\Domain\Model;

class Name
{
    private $firstName;
    private $lastName;
    private $middleName;

    public function __construct(string $firstName, string $lastName = null, string $middleName = null)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->middleName = $middleName;
    }

    public function firstName(): string
    {
        return $this->firstName;
    }

    public function lastName(): ?string
    {
        return $this->lastName;
    }

    public function middleName(): ?string
    {
        return $this->middleName;
    }
}
