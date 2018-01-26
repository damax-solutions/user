<?php

declare(strict_types=1);

namespace Damax\User\Domain\Model;

class Role
{
    private $code;
    private $name;
    private $permissions;

    public function __construct(string $code, string $name, array $permissions)
    {
        $this->code = strtolower($code);
        $this->permissions = $permissions;
    }

    public function code(): string
    {
        return $this->code;
    }

    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return string[]
     */
    public function permissions(): array
    {
        return $this->permissions;
    }

    public function __toString(): string
    {
        return $this->code;
    }
}
