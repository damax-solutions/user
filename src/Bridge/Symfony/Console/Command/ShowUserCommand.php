<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Console\Command;

use Damax\User\Application\Service\UserService;
use Damax\User\Bridge\Symfony\Console\Style;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class ShowUserCommand extends Command
{
    protected static $defaultName = 'damax:user:show';

    private $service;

    public function __construct(UserService $service)
    {
        parent::__construct();

        $this->service = $service;
    }

    protected function configure()
    {
        $this
            ->setDescription('Show user.')
            ->addArgument('user-id', InputArgument::REQUIRED, 'User id, email or mobile phone.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new Style($input, $output);
        $io->title('Show user');

        try {
            $io->user($this->service->fetch($input->getArgument('user-id')));
        } catch (Throwable $e) {
            $io->error($e->getMessage());
        }
    }
}
