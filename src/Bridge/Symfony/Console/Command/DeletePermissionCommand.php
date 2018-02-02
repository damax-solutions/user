<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Console\Command;

use Damax\User\Application\Service\PermissionService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

class DeletePermissionCommand extends Command
{
    protected static $defaultName = 'damax:user:permission:delete';

    private $service;

    public function __construct(PermissionService $service)
    {
        parent::__construct();

        $this->service = $service;
    }

    protected function configure()
    {
        $this
            ->setDescription('Delete permission.')
            ->addArgument('code', InputArgument::REQUIRED, 'Permission code.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Delete permission');

        try {
            $permission = $this->service->delete($input->getArgument('code'));
        } catch (Throwable $e) {
            $io->error($e->getMessage());

            return;
        }

        $io->table(['Field', 'Value'], [
            ['Code', $permission->code],
            ['Category', $permission->category],
            ['Description', $permission->description ?: '-'],
        ]);
    }
}
