<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Console\Command\User;

use Damax\User\Application\Dto\PasswordResetRequestDto;
use Damax\User\Application\Service\PasswordService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

class RequestPasswordResetCommand extends Command
{
    protected static $defaultName = 'damax:user:request-password-reset';

    private $service;

    public function __construct(PasswordService $service)
    {
        parent::__construct();

        $this->service = $service;
    }

    protected function configure()
    {
        $this
            ->setDescription('Initiate user password reset routine.')
            ->addArgument('user-id', InputArgument::REQUIRED, 'User id, email or mobile phone.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Request password reset');

        $request = new PasswordResetRequestDto();
        $request->userId = $input->getArgument('user-id');

        try {
            $this->service->requestPasswordReset($request);
        } catch (Throwable $e) {
            $io->error($e->getMessage());

            return;
        }

        $io->success('Password reset request initiated.');
    }
}
