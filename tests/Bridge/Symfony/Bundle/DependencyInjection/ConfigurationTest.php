<?php

declare(strict_types=1);

namespace Damax\User\Tests\Bridge\Symfony\Bundle\DependencyInjection;

use Damax\User\Bridge\Symfony\Bundle\DependencyInjection\Configuration;
use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class ConfigurationTest extends TestCase
{
    use ConfigurationTestCaseTrait;

    /**
     * @test
     */
    public function it_processes_empty_config()
    {
        $config = [
        ];

        $this->assertProcessedConfigurationEquals([$config], [
            'password_encoder' => 'security',
            'default_timezone' => 'Europe/Riga',
            'default_locale' => 'en',
            'invalidate_password' => true,
            'name_formatter' => 'standard',
            'mailer' => [
                'adapter' => 'swift',
                'sender_email' => 'no-reply@localhost',
                'sender_name' => null,
                'registration_template' => '@DamaxUser/Emails/registration.twig',
                'password_reset_template' => '@DamaxUser/Emails/password_reset_request.twig',
                'email_confirmation_template' => '@DamaxUser/Emails/email_confirmation_request.twig',
            ],
        ]);
    }

    /**
     * @test
     */
    public function it_processes_config()
    {
        $config = [
            'password_encoder' => 'plain',
            'default_timezone' => 'Europe/Moscow',
            'default_locale' => 'ru',
            'invalidate_password' => false,
            'name_formatter' => 'james_bond',
            'mailer' => [
                'adapter' => 'debug',
                'sender_email' => 'no-reply@domain.abc',
                'sender_name' => 'Administrator',
                'registration_template' => 'registration_template.html',
                'password_reset_template' => 'password_reset_template.html',
                'email_confirmation_template' => 'email_confirmation_template.html',
            ],
        ];

        $this->assertProcessedConfigurationEquals([$config], [
            'password_encoder' => 'plain',
            'default_timezone' => 'Europe/Moscow',
            'default_locale' => 'ru',
            'invalidate_password' => false,
            'name_formatter' => 'james_bond',
            'mailer' => [
                'adapter' => 'debug',
                'sender_email' => 'no-reply@domain.abc',
                'sender_name' => 'Administrator',
                'registration_template' => 'registration_template.html',
                'password_reset_template' => 'password_reset_template.html',
                'email_confirmation_template' => 'email_confirmation_template.html',
            ],
        ]);
    }

    protected function getConfiguration(): ConfigurationInterface
    {
        return new Configuration();
    }
}
