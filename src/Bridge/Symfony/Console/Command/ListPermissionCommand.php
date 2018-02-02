<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Console\Command;

use Damax\User\Application\Dto\PermissionDto;
use Damax\User\Application\Service\PermissionService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ListPermissionCommand extends Command
{
    protected static $defaultName = 'damax:user:permission:list';

    private $service;

    public function __construct(PermissionService $service)
    {
        parent::__construct();

        $this->service = $service;
    }

    protected function configure()
    {
        $this
            ->setDescription('List permissions.')
            ->addArgument('category', InputArgument::REQUIRED, 'Permission category.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('List permissions');

        $row = function (PermissionDto $permission) {
            return [$permission->code, $permission->category, $permission->description];
        };

        $io->table(['Code', 'Category', 'Description'], array_map($row, $this->service->fetchByCategory($input->getArgument('category'))));
    }
}
