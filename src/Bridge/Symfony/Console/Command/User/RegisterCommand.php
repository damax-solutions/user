<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Console\Command\User;

use Damax\User\Application\Command\RegisterUser;
use Damax\User\Application\Service\RegistrationService;
use Damax\User\Bridge\Symfony\Bundle\Form\Type\RegisterUserType;
use Damax\User\Bridge\Symfony\Console\Style;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class RegisterCommand extends Command
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
        $io = new Style($input, $output);
        $io->title('Register new user');

        /** @var RegisterUser $command */
        $command = $this->getHelper('form')->interactUsingForm(RegisterUserType::class, $input, $output);
        $command->creatorId = $input->getOption('creator-id');

        $io->newLine();

        try {
            $io->user($this->service->registerUser($command));
        } catch (Throwable $e) {
            $io->error($e->getMessage());
        }
    }
}
