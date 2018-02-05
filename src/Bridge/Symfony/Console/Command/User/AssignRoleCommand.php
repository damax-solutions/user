<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Console\Command\User;

use Damax\User\Application\Command\AssignUserRole;
use Damax\User\Application\Service\UserService;
use Damax\User\Bridge\Symfony\Console\Style;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class AssignRoleCommand extends Command
{
    protected static $defaultName = 'damax:user:assign-role';

    private $service;

    public function __construct(UserService $service)
    {
        parent::__construct();

        $this->service = $service;
    }

    protected function configure()
    {
        $this
            ->setDescription('Assign user role.')
            ->addArgument('user-id', InputArgument::REQUIRED, 'User id, email or mobile phone.')
            ->addArgument('role', InputArgument::REQUIRED, 'Role code.')
            ->addOption('editor-id', null, InputOption::VALUE_REQUIRED, 'Editor id, email or mobile phone.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new Style($input, $output);
        $io->title('Assign user role');

        $command = new AssignUserRole();
        $command->userId = $input->getArgument('user-id');
        $command->role = $input->getArgument('role');
        $command->editorId = $input->getOption('editor-id');

        try {
            $io->user($this->service->assignRole($command));
        } catch (Throwable $e) {
            $io->error($e->getMessage());
        }
    }
}
