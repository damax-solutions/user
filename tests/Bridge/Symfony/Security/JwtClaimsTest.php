<?php

declare(strict_types=1);

namespace Damax\User\Tests\Bridge\Symfony\Security;

use Damax\User\Bridge\Symfony\Security\JwtClaims;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\User;

class JwtClaimsTest extends TestCase
{
    /**
     * @test
     */
    public function it_throws_exception_when_resolving_claims_for_invalid_user()
    {
        $this->expectException(UnsupportedUserException::class);
        $this->expectExceptionMessage('User of type "Symfony\Component\Security\Core\User\User" is not supported.');

        (new JwtClaims())->resolve(new User('john.doe', 'qwerty'));
    }

    /**
     * @test
     */
    public function it_resolves_claims()
    {
        $claims = (new JwtClaims())->resolve((new UserFactory())->create());

        $this->assertEquals(['zoneinfo' => 'Europe/Riga', 'locale' => 'ru'], $claims);
    }
}
