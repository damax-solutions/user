<?php

declare(strict_types=1);

namespace Damax\User\Domain\Password;

use Damax\User\Domain\Model\Password;

class PlainEncoder implements Encoder
{
    private $salt;

    public function __construct(string $salt = '')
    {
        $this->salt = $salt;
    }

    public function encode(string $password): Password
    {
        return Password::valid3Months($password, $this->salt);
    }

    public function produceSalt(): string
    {
        return $this->salt;
    }
}
