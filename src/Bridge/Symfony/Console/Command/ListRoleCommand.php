<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Console\Command;

use Damax\User\Application\Dto\RoleDto;
use Damax\User\Application\Service\RoleService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ListRoleCommand extends Command
{
    protected static $defaultName = 'damax:user:role:list';

    private $service;

    public function __construct(RoleService $service)
    {
        parent::__construct();

        $this->service = $service;
    }

    protected function configure()
    {
        $this->setDescription('List roles.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('List roles');

        $row = function (RoleDto $role) {
            return [$role->code, $role->name];
        };

        $io->table(['Code', 'Name'], array_map($row, $this->service->fetchAll()));
    }
}
