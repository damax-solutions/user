<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Bundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('damax_user');
        $rootNode
            ->children()
                ->enumNode('password_encoder')
                    ->values(['plain', 'security'])
                    ->defaultValue('security')
                ->end()

                ->scalarNode('default_locale')
                    ->cannotBeEmpty()
                    ->defaultValue('en')
                ->end()

                ->enumNode('default_timezone')
                    ->values(timezone_identifiers_list())
                    ->defaultValue('Europe/Riga')
                ->end()

                ->booleanNode('invalidate_password')->defaultTrue()->end()

                /*
                ->enumNode('mailer')
                    ->values(['debug', 'swift'])
                    ->defaultValue('debug')
                ->end()
                */
            ->end()
        ;

        return $treeBuilder;
    }
}
