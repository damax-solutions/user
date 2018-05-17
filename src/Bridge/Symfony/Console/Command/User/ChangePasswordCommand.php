<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Console\Command\User;

use Damax\User\Application\Command\ChangePassword;
use Damax\User\Application\Service\PasswordService;
use Damax\User\Bridge\Symfony\Validator\Constraints\Password;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

class ChangePasswordCommand extends Command
{
    protected static $defaultName = 'damax:user:change-password';

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
            ->setDescription('Change user password.')
            ->addArgument('user-id', InputArgument::REQUIRED, 'User id, email or mobile phone.')
            ->addArgument('password', InputArgument::REQUIRED, 'New password.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Change user password');

        $contraints = [
            new NotBlank(),
            new Password(),
        ];

        $password = $input->getArgument('password');

        if (count($errors = $this->validator->validate($password, $contraints))) {
            foreach ($errors as $error) {
                $io->error($error->getMessage());
            }

            return;
        }

        $command = new ChangePassword();
        $command->userId = $input->getArgument('user-id');
        $command->password = $password;

        try {
            $this->service->changePassword($command);
        } catch (Throwable $e) {
            $io->error($e->getMessage());

            return;
        }

        $io->success('Password changed.');
    }
}
