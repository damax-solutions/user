<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Console\Command;

use Damax\User\Application\Command\CreateRole;
use Damax\User\Application\Dto\RoleDto;
use Damax\User\Application\Service\RoleService;
use Damax\User\Bridge\Symfony\Bundle\Form\Type\RoleType;
use Damax\User\Bridge\Symfony\Console\Style;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class CreateRoleCommand extends Command
{
    protected static $defaultName = 'damax:user:role:create';

    private $service;

    public function __construct(RoleService $service)
    {
        parent::__construct();

        $this->service = $service;
    }

    protected function configure()
    {
        $this->setDescription('Create role.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new Style($input, $output);
        $io->title('Create role');

        /** @var RoleDto $dto */
        $dto = $this->getHelper('form')->interactUsingForm(RoleType::class, $input, $output);

        $command = new CreateRole();
        $command->role = $dto;

        $io->newLine();

        try {
            $io->role($this->service->create($command));
        } catch (Throwable $e) {
            $io->error($e->getMessage());
        }
    }
}
