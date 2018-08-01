<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Bundle\DependencyInjection;

use Damax\User\Bridge\Mailer\SwiftMailer;
use Damax\User\Domain\Configuration as UserConfiguration;
use Damax\User\Domain\Mailer\DebugMailer;
use Damax\User\Domain\Mailer\Mailer;
use Damax\User\Domain\Model\ActionRequest;
use Damax\User\Domain\Model\Locale;
use Damax\User\Domain\Model\LoginHistory;
use Damax\User\Domain\Model\Permission;
use Damax\User\Domain\Model\Role;
use Damax\User\Domain\Model\Timezone;
use Damax\User\Domain\NameFormatter\NameFormatter;
use Doctrine\Common\Inflector\Inflector;
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
        $loader->load('in-memory.xml');
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

        $this
            ->configureNameFormatter($config, $container)
            ->configureMailer($config['mailer'], $container)
            ->configureMapping($config['mapping'], $container)
        ;

        $container->setParameter('damax.user.locales', $config['locales']);
        $container->setParameter('damax.user.timezones', $config['timezones']);
        $container->setParameter('damax.user.security.username_accessor', $config['security']['username_accessor']);
    }

    private function configureNameFormatter(array $config, ContainerBuilder $container): self
    {
        $container->setAlias(NameFormatter::class, 'Damax\\User\\Domain\\NameFormatter\\' . Inflector::classify($config['name_formatter']) . 'NameFormatter');

        return $this;
    }

    private function configureMailer(array $config, ContainerBuilder $container): self
    {
        if ('swift' === $config['adapter']) {
            unset($config['adapter']);

            $container
                ->autowire(Mailer::class, SwiftMailer::class)
                ->setBindings(['$mailerOptions' => $config])
            ;
        } else {
            $container->autowire(Mailer::class, DebugMailer::class);
        }

        return $this;
    }

    private function configureMapping(array $config, ContainerBuilder $container): self
    {
        $container->setParameter('damax.user.user_class', $config['user_class']);
        $container->setParameter('damax.user.login_history_class', LoginHistory::class);
        $container->setParameter('damax.user.permission_class', Permission::class);
        $container->setParameter('damax.user.role_class', Role::class);
        $container->setParameter('damax.user.action_request_class', ActionRequest::class);

        if (empty($config['enabled'])) {
            $container->setParameter('damax.user.mapping.doctrine.default', true);
        }

        return $this;
    }
}
