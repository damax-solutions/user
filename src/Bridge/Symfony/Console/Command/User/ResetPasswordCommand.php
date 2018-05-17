<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Console\Command\User;

use Damax\User\Application\Dto\PasswordResetDto;
use Damax\User\Application\Service\PasswordService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

class ResetPasswordCommand extends Command
{
    protected static $defaultName = 'damax:user:reset-password';

    private $service;
    private $validator;

    public function __construct(PasswordService $service, ValidatorInterface $validator)
    {
        parent::__construct();

        $this->service = $service;
        $this->validator = $validator;
    }

    protected function configure()
    {
        $this
            ->setDescription('Reset user password.')
            ->addArgument('token', InputArgument::REQUIRED, 'Password reset token.')
            ->addArgument('password', InputArgument::REQUIRED, 'New password.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Reset user password');

        $reset = new PasswordResetDto();
        $reset->token = $input->getArgument('token');
        $reset->newPassword = $input->getArgument('password');

        if (count($errors = $this->validator->validate($reset))) {
            foreach ($errors as $error) {
                $io->error($error->getMessage());
            }

            return;
        }

        try {
            $this->service->resetPassword($reset);
        } catch (Throwable $e) {
            $io->error($e->getMessage());

            return;
        }

        $io->success('Password changed.');
    }
}
