<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Console\Command;

use Damax\User\Application\Service\RoleService;
use Damax\User\Bridge\Symfony\Console\Style;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class ShowRoleCommand extends Command
{
    protected static $defaultName = 'damax:user:role:show';

    private $service;

    public function __construct(RoleService $service)
    {
        parent::__construct();

        $this->service = $service;
    }

    protected function configure()
    {
        $this
            ->setDescription('Show role.')
            ->addArgument('code', InputArgument::REQUIRED, 'Role code.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new Style($input, $output);
        $io->title('Show role');

        try {
            $io->role($this->service->fetch($input->getArgument('code')));
        } catch (Throwable $e) {
            $io->error($e->getMessage());
        }
    }
}
