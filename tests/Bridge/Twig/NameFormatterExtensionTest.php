<?php

declare(strict_types=1);

namespace Damax\User\Tests\Bridge\Twig;

use Damax\User\Bridge\Twig\NameFormatterExtension;
use Damax\User\Domain\Model\Name;
use Damax\User\Domain\NameFormatter\JamesBondNameFormatter;
use PHPUnit\Framework\TestCase;
use Twig_Environment;
use Twig_Loader_Array;

class NameFormatterExtensionTest extends TestCase
{
    /**
     * @var Twig_Loader_Array
     */
    private $loader;

    /**
     * @var Twig_Environment
     */
    private $twig;

    protected function setUp()
    {
        $this->twig = new Twig_Environment($this->loader = new Twig_Loader_Array(), ['debug' => true, 'cache' => false]);
        $this->twig->addExtension(new NameFormatterExtension(new JamesBondNameFormatter()));
    }

    /**
     * @test
     */
    public function it_formats_name()
    {
        $this->loader->setTemplate('index', '<p>{{ name | user_format_name }}</p>');

        $template = $this->twig->render('index', [
            'name' => Name::fromArray(['first_name' => 'James', 'last_name' => 'Bond']),
        ]);

        $this->assertEquals('<p>Bond, James Bond</p>', $template);
        $this->assertEquals('<p></p>', $this->twig->render('index', ['name' => Name::fromArray([])]));
    }
}
