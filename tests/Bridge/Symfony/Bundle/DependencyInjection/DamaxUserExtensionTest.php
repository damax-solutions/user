<?php

declare(strict_types=1);

namespace Damax\User\Tests\Bridge\Symfony\Bundle\DependencyInjection;

use Damax\User\Bridge\Mailer\SwiftMailer;
use Damax\User\Bridge\Symfony\Bundle\DependencyInjection\DamaxUserExtension;
use Damax\User\Domain\Configuration;
use Damax\User\Domain\Mailer\Mailer;
use Damax\User\Domain\Model\Locale;
use Damax\User\Domain\Model\Timezone;
use Damax\User\Domain\NameFormatter\JamesBondNameFormatter;
use Damax\User\Domain\NameFormatter\NameFormatter;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

class DamaxUserExtensionTest extends AbstractExtensionTestCase
{
    /**
     * @test
     */
    public function it_registers_services()
    {
        $this->load([
            'default_locale' => 'ru',
            'default_timezone' => 'Europe/Moscow',
            'invalidate_password' => false,
            'name_formatter' => 'james_bond',
            'mailer' => [
                'adapter' => 'swift',
            ],
        ]);

        $configuration = $this->container->getDefinition(Configuration::class);

        $this->assertEquals('Europe/Moscow', $configuration->getArgument(0)->getArgument(0));
        $this->assertEquals(Timezone::class, $configuration->getArgument(0)->getClass());
        $this->assertEquals('ru', $configuration->getArgument(1)->getArgument(0));
        $this->assertEquals(Locale::class, $configuration->getArgument(1)->getClass());
        $this->assertFalse($configuration->getArgument(2));

        $this->assertContainerBuilderHasParameter('damax.user.user_class');
        $this->assertContainerBuilderHasParameter('damax.user.login_history_class');
        $this->assertContainerBuilderHasParameter('damax.user.security.username_accessor', 'mobilePhone');

        $this->assertContainerBuilderHasAlias(NameFormatter::class, JamesBondNameFormatter::class);
        $this->assertContainerBuilderHasService(Mailer::class, SwiftMailer::class);
    }

    protected function getContainerExtensions(): array
    {
        return [
            new DamaxUserExtension(),
        ];
    }
}
