<?php

declare(strict_types=1);

namespace Damax\User\Domain\Model;

use DateTimeZone;

final class Timezone
{
    private $id;

    public static function fromId(string $id)
    {
        return new self($id);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function dateTimeZone(): DateTimeZone
    {
        return new DateTimeZone($this->id);
    }

    public function __toString(): string
    {
        return $this->id;
    }

    private function __construct(string $id)
    {
        $this->id = strtolower($id);
    }
}
