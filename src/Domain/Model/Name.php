<?php

declare(strict_types=1);

namespace Damax\User\Domain\Model;

class Name
{
    private $firstName;
    private $lastName;
    private $middleName;

    public static function fromArray(array $data): self
    {
        return new self($data['first_name'] ?? null, $data['last_name'] ?? null, $data['middle_name'] ?? null);
    }

    public function __construct(string $firstName = null, string $lastName = null, string $middleName = null)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->middleName = $middleName;
    }

    public function firstName(): ?string
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
