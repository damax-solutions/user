<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Bundle\DependencyInjection;

use Damax\User\Domain\Configuration as UserConfiguration;
use Damax\User\Domain\Model\Locale;
use Damax\User\Domain\Model\Timezone;
use Damax\User\Domain\Model\User;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class DamaxUserExtension extends ConfigurableExtension
{
    protected function loadInternal(array $config, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');
        $loader->load('doctrine-orm.xml');
        $loader->load('security.xml');

        $timezone = (new Definition(Timezone::class))
            ->setFactory([Timezone::class, 'fromId'])
            ->addArgument($config['default_timezone'])
        ;
        $locale = (new Definition(Locale::class))
            ->setFactory([Locale::class, 'fromCode'])
            ->addArgument($config['default_locale'])
        ;
        $container
            ->getDefinition(UserConfiguration::class)
            ->addArgument($timezone)
            ->addArgument($locale)
            ->addArgument($config['invalidate_password'])
        ;

        $container->setParameter('damax.user.user_class', User::class);
    }
}
