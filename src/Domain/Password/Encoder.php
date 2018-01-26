<?php

declare(strict_types=1);

namespace Damax\User\Domain\Password;

use Damax\User\Domain\Model\Password;

interface Encoder
{
    public function encode(string $password): Password;

    public function produceSalt(): string;
}
