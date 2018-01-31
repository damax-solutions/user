<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Console\Command;

use Damax\User\Application\Service\UserService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
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
        $io = new SymfonyStyle($input, $output);
        $io->title('Show user');

        try {
            $user = $this->service->fetch($input->getArgument('user-id'));
        } catch (Throwable $e) {
            $io->error($e->getMessage());

            return;
        }

        $io->table(['Field', 'Value'], [
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
}
