<?php

declare(strict_types=1);

namespace Damax\User\Application\Service;

use Damax\Common\Pagerfanta\CallableDecoratorAdapter;
use Damax\User\Application\Command\AssignUserRole;
use Damax\User\Application\Command\DisableUser;
use Damax\User\Application\Command\EnableUser;
use Damax\User\Application\Command\RemoveUserRole;
use Damax\User\Application\Command\UpdateUser;
use Damax\User\Application\Dto\Assembler;
use Damax\User\Application\Dto\UserDto;
use Damax\User\Domain\Model\Locale;
use Damax\User\Domain\Model\Name;
use Damax\User\Domain\Model\RoleRepository;
use Damax\User\Domain\Model\Timezone;
use Damax\User\Domain\Model\UserRepository;
use Pagerfanta\Pagerfanta;

class UserService
{
    use UserServiceTrait;
    use RoleServiceTrait;

    private $assembler;

    public function __construct(UserRepository $users, RoleRepository $roles, Assembler $assembler)
    {
        $this->users = $users;
        $this->roles = $roles;
        $this->assembler = $assembler;
    }

    public function fetch(string $userId): UserDto
    {
        $user = $this->getUser($userId);

        return $this->assembler->toUserDto($user);
    }

    public function fetchRange(): Pagerfanta
    {
        $adapter = $this->users->paginate()->getAdapter();

        return new Pagerfanta(new CallableDecoratorAdapter($adapter, [$this->assembler, 'toUserDto']));
    }

    public function enable(EnableUser $command): UserDto
    {
        $editor = $this->getUser($command->editorId);

        $user = $this->getUser($command->userId);
        $user->enable($editor);

        $this->users->save($user);

        return $this->assembler->toUserDto($user);
    }

    public function disable(DisableUser $command): UserDto
    {
        $editor = $this->getUser($command->editorId);

        $user = $this->getUser($command->userId);
        $user->disable($editor);

        $this->users->save($user);

        return $this->assembler->toUserDto($user);
    }

    public function update(UpdateUser $command): UserDto
    {
        $editor = $command->editorId ? $this->getUser($command->editorId) : null;

        $name = Name::fromArray($command->name);
        $timezone = Timezone::fromId($command->timezone);
        $locale = Locale::fromCode($command->locale);

        $user = $this->getUser($command->userId);
        $user->update($name, $timezone, $locale, $editor);

        $this->users->save($user);

        return $this->assembler->toUserDto($user);
    }

    public function assignRole(AssignUserRole $command): UserDto
    {
        $editor = $command->editorId ? $this->getUser($command->editorId) : null;

        $user = $this->getUser($command->userId);
        $role = $this->getRole($command->role);

        $user->assignRole($role, $editor);

        $this->users->save($user);

        return $this->assembler->toUserDto($user);
    }

    public function removeRole(RemoveUserRole $command): UserDto
    {
        $editor = $command->editorId ? $this->getUser($command->editorId) : null;

        $user = $this->getUser($command->userId);
        $role = $this->getRole($command->role);

        $user->removeRole($role, $editor);

        $this->users->save($user);

        return $this->assembler->toUserDto($user);
    }
}
