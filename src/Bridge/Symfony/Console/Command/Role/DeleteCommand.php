<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Console\Command\Role;

use Damax\User\Application\Service\RoleService;
use Damax\User\Bridge\Symfony\Console\Style;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class DeleteCommand extends Command
{
    protected static $defaultName = 'damax:user:role:delete';

    private $service;

    public function __construct(RoleService $service)
    {
        parent::__construct();

        $this->service = $service;
    }

    protected function configure()
    {
        $this
            ->setDescription('Delete role.')
            ->addArgument('code', InputArgument::REQUIRED, 'Role code.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new Style($input, $output);
        $io->title('Delete role');

        try {
            $io->role($this->service->delete($input->getArgument('code')));
            $io->success('Role deleted.');
        } catch (Throwable $e) {
            $io->error($e->getMessage());
        }
    }
}
