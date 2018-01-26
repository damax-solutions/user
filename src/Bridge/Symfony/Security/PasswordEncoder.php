<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Security;

use Damax\User\Domain\Model\Password;
use Damax\User\Domain\Password\Encoder;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class PasswordEncoder implements Encoder
{
    private $encoder;

    public function __construct(PasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function encode(string $password): Password
    {
        $salt = $this->produceSalt();

        return Password::valid3Months($this->encoder->encodePassword($password, $salt), $salt);
    }

    public function produceSalt(): string
    {
        return base_convert(sha1(uniqid((string) mt_rand(), true)), 16, 36);
    }
}
