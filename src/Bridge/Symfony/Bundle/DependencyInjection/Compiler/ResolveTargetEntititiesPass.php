<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Bundle\DependencyInjection\Compiler;

use Damax\User\Domain\Model\User;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ResolveTargetEntititiesPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('doctrine.orm.listeners.resolve_target_entity')) {
            return;
        }

        $container
            ->getDefinition('doctrine.orm.listeners.resolve_target_entity')
            ->addMethodCall('addResolveTargetEntity', [
                User::class,
                $container->getParameter('damax.user.user_class'),
                [],
            ])
        ;
    }
}
