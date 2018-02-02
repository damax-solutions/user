<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Console\Command;

use Damax\User\Application\Command\RegisterUser;
use Damax\User\Application\Service\RegistrationService;
use Damax\User\Bridge\Symfony\Bundle\Form\Type\RegisterUserType;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

class RegisterUserCommand extends Command
{
    protected static $defaultName = 'damax:user:register';

    private $service;

    public function __construct(RegistrationService $service)
    {
        parent::__construct();

        $this->service = $service;
    }

    protected function configure()
    {
        $this
            ->setDescription('Register new user.')
            ->addOption('creator-id', null, InputOption::VALUE_REQUIRED, 'Creator id, email or mobile phone.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Register new user');

        /** @var RegisterUser $command */
        $command = $this->getHelper('form')->interactUsingForm(RegisterUserType::class, $input, $output);
        $command->creatorId = $input->getOption('creator-id');

        try {
            $user = $this->service->registerUser($command);
        } catch (Throwable $e) {
            $io->newLine();
            $io->error($e->getMessage());

            return;
        }

        $io->newLine();
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
