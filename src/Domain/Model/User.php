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
    private $createdBy;
    private $updatedBy;
    private $enabled = true;
    private $lastLoginAt;

    public function __construct(UuidInterface $id, Email $email, MobilePhone $mobilePhone, Password $password, Name $name, Timezone $timezone, Locale $locale, User $creator = null)
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
        $this->createdBy = $this->updatedBy = $creator ?? $this;
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
        return array_reduce($this->roles(), function (array $acc, Role $role): array {
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

    public function createdBy(): User
    {
        return $this->createdBy;
    }

    public function updatedBy(): User
    {
        return $this->updatedBy;
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

    public function changePassword(Password $password, User $editor = null)
    {
        $this->password = $password;
        $this->updatedAt = new DateTimeImmutable();
        $this->updatedBy = $editor ?? $this;
    }

    public function changeInfo(Name $name, Timezone $timezone, Locale $locale, User $editor = null)
    {
        $this->name = $name;
        $this->timezone = $timezone;
        $this->locale = $locale;
        $this->updatedAt = new DateTimeImmutable();
        $this->updatedBy = $editor ?? $this;
    }

    public function enable(User $editor)
    {
        if (!$this->enabled) {
            $this->enabled = true;
            $this->updatedAt = new DateTimeImmutable();
            $this->updatedBy = $editor;
        }
    }

    public function disable(User $editor)
    {
        if ($this->enabled) {
            $this->enabled = false;
            $this->updatedAt = new DateTimeImmutable();
            $this->updatedBy = $editor;
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
