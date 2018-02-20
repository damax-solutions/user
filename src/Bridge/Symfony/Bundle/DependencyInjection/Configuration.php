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

        $emailValidator = function (string $email): bool {
            return !filter_var($email, FILTER_VALIDATE_EMAIL);
        };

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

                ->enumNode('name_formatter')
                    ->values(['standard', 'russian', 'james_bond'])
                    ->defaultValue('standard')
                ->end()

                ->arrayNode('mailer')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->enumNode('adapter')
                            ->values(['debug', 'swift'])
                            ->defaultValue('swift')
                        ->end()
                        ->scalarNode('sender_email')
                            ->validate()
                                ->ifTrue($emailValidator)
                                ->thenInvalid('Invalid email.')
                            ->end()
                            ->cannotBeEmpty()
                            ->defaultValue('no-reply@localhost')
                        ->end()
                        ->scalarNode('sender_name')
                            ->cannotBeEmpty()
                            ->defaultNull()
                        ->end()
                        ->scalarNode('registration_template')
                            ->cannotBeEmpty()
                            ->defaultValue('@DamaxUser/Emails/registration.twig')
                        ->end()
                        ->scalarNode('password_reset_template')
                            ->cannotBeEmpty()
                            ->defaultValue('@DamaxUser/Emails/password_reset_request.twig')
                        ->end()
                        ->scalarNode('email_confirmation_template')
                            ->cannotBeEmpty()
                            ->defaultValue('@DamaxUser/Emails/email_confirmation_request.twig')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
