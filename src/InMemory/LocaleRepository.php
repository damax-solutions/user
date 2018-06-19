<?php

declare(strict_types=1);

namespace Damax\User\InMemory;

use Damax\User\Domain\Model\Locale;
use Damax\User\Domain\Model\LocaleRepository as LocaleRepositoryInterface;

class LocaleRepository implements LocaleRepositoryInterface
{
    private $codes;

    public function __construct(array $localeCodes)
    {
        $this->codes = $localeCodes;
    }

    public function byCode(string $code): ?Locale
    {
        return in_array($code, $this->codes) ? Locale::fromCode($code) : null;
    }

    public function all(): array
    {
        return array_map([Locale::class, 'fromCode'], $this->codes);
    }
}
