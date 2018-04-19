<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Security;

use IntlDateFormatter;
use Serializable;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class User implements AdvancedUserInterface, EquatableInterface, Serializable
{
    private const DEFAULT_ROLES = ['ROLE_MEMBER'];

    private $id;
    private $username;
    private $roles;
    private $password;
    private $salt;
    private $passwordExpired;
    private $timezone;
    private $locale;
    private $enabled;

    public function __construct(string $id, string $username, array $roles, string $password, string $salt, bool $passwordExpired, string $timezone, string $locale, bool $enabled)
    {
        $this->id = $id;
        $this->username = $username;
        $this->roles = array_merge($roles, self::DEFAULT_ROLES);
        $this->password = $password;
        $this->salt = $salt;
        $this->passwordExpired = $passwordExpired;
        $this->timezone = $timezone;
        $this->locale = $locale;
        $this->enabled = $enabled;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getSalt(): string
    {
        return $this->salt;
    }

    public function isAccountNonExpired(): bool
    {
        return true;
    }

    public function isAccountNonLocked(): bool
    {
        return true;
    }

    public function isCredentialsNonExpired(): bool
    {
        return !$this->passwordExpired;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function getTimezone(): string
    {
        return $this->timezone;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function eraseCredentials()
    {
        $this->password = '';
        $this->salt = '';
    }

    public function isEqualTo(UserInterface $user): bool
    {
        if (!$user instanceof self) {
            return false;
        }

        return $this->getId() === $user->getId();
    }

    public function serialize(): string
    {
        return serialize([
            $this->id,
            $this->username,
            $this->roles,
            $this->password,
            $this->salt,
            $this->passwordExpired,
            $this->timezone,
            $this->locale,
            $this->enabled,
        ]);
    }

    public function unserialize($serialized): void
    {
        list(
            $this->id,
            $this->username,
            $this->roles,
            $this->password,
            $this->salt,
            $this->passwordExpired,
            $this->timezone,
            $this->locale,
            $this->enabled
        ) = unserialize($serialized);
    }

    public function getDateFormatter(int $dateType = IntlDateFormatter::MEDIUM, int $timeType = IntlDateFormatter::MEDIUM): IntlDateFormatter
    {
        return IntlDateFormatter::create($this->locale, $dateType, $timeType, $this->timezone);
    }
}
