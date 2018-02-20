<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Console\Command\User;

use Damax\User\Application\Command\ConfirmEmail;
use Damax\User\Application\Service\ConfirmationService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

class ConfirmEmailCommand extends Command
{
    protected static $defaultName = 'damax:user:confirm-email';

    private $service;

    public function __construct(ConfirmationService $service)
    {
        parent::__construct();

        $this->service = $service;
    }

    protected function configure()
    {
        $this
            ->setDescription('Confirm user email.')
            ->addArgument('token', InputArgument::REQUIRED, 'Email confirmation token.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Confirm user email');

        $command = new ConfirmEmail();
        $command->token = $input->getArgument('token');

        try {
            $this->service->confirmEmail($command);
        } catch (Throwable $e) {
            $io->error($e->getMessage());

            return;
        }

        $io->success('Email confirmed.');
    }
}
