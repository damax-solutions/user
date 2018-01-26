<?php

declare(strict_types=1);

namespace Damax\User\Domain\Model;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\UuidInterface;

class User
{
    private $id;
    private $roles;
    private $email;
    private $mobilePhone;
    private $password;
    private $name;
    private $timezone;
    private $locale;
    private $createdAt;
    private $updatedAt;
    private $enabled = true;
    private $lastLoginAt;

    public function __construct(UuidInterface $id, Email $email, MobilePhone $mobilePhone, Password $password, Name $name, Timezone $timezone, Locale $locale)
    {
        $this->id = $id;
        $this->roles = new ArrayCollection();
        $this->email = $email;
        $this->mobilePhone = $mobilePhone;
        $this->password = $password;
        $this->name = $name;
        $this->timezone = $timezone;
        $this->locale = $locale;
        $this->createdAt = $this->updatedAt = new DateTimeImmutable();
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return Role[]
     */
    public function roles(): array
    {
        return $this->roles->toArray();
    }

    public function addRole(Role $role)
    {
        if (!$this->roles->contains($role)) {
            $this->roles->add($role);
        }
    }

    public function removeRole(Role $role)
    {
        if ($this->roles->contains($role)) {
            $this->roles->removeElement($role);
        }
    }

    /**
     * @return string[]
     */
    public function permissions(): array
    {
        return array_reduce($this->roles(), function (array $acc, Role $role) {
            return array_merge($acc, $role->permissions());
        }, []);
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function mobilePhone(): MobilePhone
    {
        return $this->mobilePhone;
    }

    public function password(): Password
    {
        return $this->password;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function timezone(): Timezone
    {
        return $this->timezone;
    }

    public function locale(): Locale
    {
        return $this->locale;
    }

    public function createdAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function enabled(): bool
    {
        return $this->enabled;
    }

    public function invalidatePassword()
    {
        $this->password = $this->password()->invalidate();
        $this->updatedAt = new DateTimeImmutable();
    }

    public function changePassword(Password $password)
    {
        $this->password = $password;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function changeProfileInfo(Name $name, Timezone $timezone, Locale $locale)
    {
        $this->name = $name;
        $this->timezone = $timezone;
        $this->locale = $locale;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function enable()
    {
        if (!$this->enabled) {
            $this->enabled = true;
            $this->updatedAt = new DateTimeImmutable();
        }
    }

    public function disable()
    {
        if ($this->enabled) {
            $this->enabled = false;
            $this->updatedAt = new DateTimeImmutable();
        }
    }

    public function lastLoginAt(): ?DateTimeInterface
    {
        return $this->lastLoginAt;
    }

    public function loggedInOn(DateTimeInterface $loginAt)
    {
        $this->lastLoginAt = $loginAt;
    }

    public function sameIdentityAs(self $user): bool
    {
        return $this->email->sameAs($user->email()) || $this->mobilePhone->sameAs($user->mobilePhone());
    }
}
