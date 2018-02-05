<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Console\Command\Permission;

use Damax\User\Application\Service\PermissionService;
use Damax\User\Bridge\Symfony\Console\Style;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class DeleteCommand extends Command
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
        $io = new Style($input, $output);
        $io->title('Delete permission');

        try {
            $io->permission($this->service->delete($input->getArgument('code')));
            $io->success('Permission deleted.');
        } catch (Throwable $e) {
            $io->error($e->getMessage());
        }
    }
}
