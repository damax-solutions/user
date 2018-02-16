<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Twig;

use Damax\User\Domain\Model\Name;
use Damax\User\Domain\NameFormatter\NameFormatter;
use Twig_Extension;
use Twig_Filter;

class NameFormatterExtension extends Twig_Extension
{
    private $nameFormatter;

    public function __construct(NameFormatter $nameFormatter)
    {
        $this->nameFormatter = $nameFormatter;
    }

    public function getFilters(): array
    {
        return [
            new Twig_Filter('user_format_name', [$this, 'formatName']),
        ];
    }

    public function formatName(Name $name, string $format = 'full'): ?string
    {
        return call_user_func([$this->nameFormatter, $format], $name);
    }
}
