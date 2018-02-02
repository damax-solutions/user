<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Console;

use BadMethodCallException;
use Damax\User\Application\Dto\PermissionDto;
use Damax\User\Application\Dto\RoleDto;
use Damax\User\Application\Dto\UserDto;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @method title(string $message)
 * @method success(string $message)
 * @method error(string $message)
 * @method newLine(int $count = 1)
 */
class Style
{
    private $io;

    public function __construct(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    public function user(UserDto $user)
    {
        $this->io->table(['Field', 'Value'], [
            ['Id', $user->id],
            ['Email', $user->email],
            ['Mobile', $user->mobilePhone],
            ['First name', $user->name->firstName ?? '-'],
            ['Last name', $user->name->lastName ?? '-'],
            ['Middle name', $user->name->middleName ?? '-'],
            ['Timezone', $user->timezone],
            ['Locale', $user->locale],
            ['Enabled', $user->enabled ? '+' : '-'],
        ]);
    }

    public function role(RoleDto $role)
    {
        $this->io->table(['Field', 'Value'], [
            ['Code', $role->code],
            ['Name', $role->name],
            ['Permissions', implode("\n", $role->permissions)],
        ]);
    }

    public function permission(PermissionDto $permission)
    {
        $this->io->table(['Field', 'Value'], [
            ['Code', $permission->code],
            ['Category', $permission->category],
            ['Description', $permission->description ?: '-'],
        ]);
    }

    public function __call(string $name, array $arguments)
    {
        if (!method_exists($this->io, $name)) {
            throw new BadMethodCallException("Method '%s::%s' not implemented.", get_class($this->io), $name);
        }

        return call_user_func_array([$this->io, $name], $arguments);
    }
}
