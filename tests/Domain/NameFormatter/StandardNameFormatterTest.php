<?php

declare(strict_types=1);

namespace Damax\User\Tests\Domain\NameFormatter;

use Damax\User\Domain\Model\Name;
use Damax\User\Domain\NameFormatter\StandardNameFormatter;
use PHPUnit\Framework\TestCase;

class StandardNameFormatterTest extends TestCase
{
    /**
     * @dataProvider provideNameData
     *
     * @test
     */
    public function it_formats_full_name(Name $name, ?string $full)
    {
        $this->assertSame($full, (new StandardNameFormatter())->full($name));
    }

    public function provideNameData(): array
    {
        return [
            [Name::fromArray([]), null],
            [Name::fromArray(['first_name' => 'Dmitri']), 'Dmitri'],
            [Name::fromArray(['last_name' => 'Lakachauskis']), 'Lakachauskis'],
            [Name::fromArray(['first_name' => 'Dmitri', 'last_name' => 'Lakachauskis']), 'Dmitri Lakachauskis'],
            [Name::fromArray(['first_name' => 'Dmitri', 'middle_name' => 'Arnold']), 'Dmitri Arnold'],
            [Name::fromArray(['first_name' => 'Dmitri', 'last_name' => 'Lakachauskis', 'middle_name' => 'Arnold']), 'Dmitri Arnold Lakachauskis'],
        ];
    }
}
