<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Console\Command\Permission;

use Damax\User\Application\Command\CreatePermission;
use Damax\User\Application\Dto\PermissionDto;
use Damax\User\Application\Service\PermissionService;
use Damax\User\Bridge\Symfony\Bundle\Form\Type\PermissionType;
use Damax\User\Bridge\Symfony\Console\Style;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class CreateCommand extends Command
{
    protected static $defaultName = 'damax:user:permission:create';

    private $service;

    public function __construct(PermissionService $service)
    {
        parent::__construct();

        $this->service = $service;
    }

    protected function configure()
    {
        $this->setDescription('Create permission.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new Style($input, $output);
        $io->title('Create permission');

        /** @var PermissionDto $permission */
        $permission = $this->getHelper('form')->interactUsingForm(PermissionType::class, $input, $output);

        $command = new CreatePermission();
        $command->permission = $permission;

        $io->newLine();

        try {
            $io->permission($this->service->create($command));
        } catch (Throwable $e) {
            $io->error($e->getMessage());
        }
    }
}
