<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Bundle;

use Damax\User\Bridge\Symfony\Bundle\DependencyInjection\Compiler\ResolveTargetEntititiesPass;
use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DamaxUserBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $this->registerDoctrineMapping($container);
    }

    private function registerDoctrineMapping(ContainerBuilder $container)
    {
        $model = [
            realpath(__DIR__ . '/Resources/config/doctrine') => 'Damax\\User\\Domain\\Model',
        ];
        $default = [
            realpath(__DIR__ . '/Resources/config/doctrine-default') => 'Damax\\User\\Doctrine\\Orm',
        ];

        $container
            ->addCompilerPass(DoctrineOrmMappingsPass::createXmlMappingDriver($model))
            ->addCompilerPass(DoctrineOrmMappingsPass::createXmlMappingDriver($default, [], 'damax.user.mapping.doctrine.default'))
            ->addCompilerPass(new ResolveTargetEntititiesPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 4)
        ;
    }
}
