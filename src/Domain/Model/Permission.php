<?php

declare(strict_types=1);

namespace Damax\User\Domain\Model;

class Permission
{
    private $code;
    private $category;
    private $description;

    public function __construct(string $code, string $category, string $description = null)
    {
        $this->code = strtolower($code);
        $this->category = $category;
        $this->description = $description;
    }

    public function code(): string
    {
        return $this->code;
    }

    public function category(): string
    {
        return $this->category;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function __toString(): string
    {
        return $this->code;
    }
}
