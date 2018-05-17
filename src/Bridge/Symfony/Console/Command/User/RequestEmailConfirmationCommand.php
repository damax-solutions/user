<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Console\Command\User;

use Damax\User\Application\Dto\EmailConfirmationRequestDto;
use Damax\User\Application\Service\ConfirmationService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

class RequestEmailConfirmationCommand extends Command
{
    protected static $defaultName = 'damax:user:request-email-confirmation';

    private $service;

    public function __construct(ConfirmationService $service)
    {
        parent::__construct();

        $this->service = $service;
    }

    protected function configure()
    {
        $this
            ->setDescription('Initiate user email confirmation routine.')
            ->addArgument('user-id', InputArgument::REQUIRED, 'User id, email or mobile phone.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Request email confirmation');

        $request = new EmailConfirmationRequestDto();
        $request->userId = $input->getArgument('user-id');

        try {
            $this->service->requestEmailConfirmation($request);
        } catch (Throwable $e) {
            $io->error($e->getMessage());

            return;
        }

        $io->success('Email confirmation request initiated.');
    }
}
