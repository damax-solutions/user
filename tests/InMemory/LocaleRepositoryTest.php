<?php

declare(strict_types=1);

namespace Damax\User\Tests\InMemory;

use Damax\User\InMemory\LocaleRepository;
use PHPUnit\Framework\TestCase;

class LocaleRepositoryTest extends TestCase
{
    /**
     * @var LocaleRepository
     */
    private $repository;

    protected function setUp()
    {
        $this->repository = new LocaleRepository(['en', 'ru']);
    }

    /**
     * @test
     */
    public function it_fetches_by_code()
    {
        $this->assertEquals('en', $this->repository->byCode('en')->code());
        $this->assertNull($this->repository->byCode('de'));
    }

    /**
     * @test
     */
    public function it_fetches_all()
    {
        $locales = $this->repository->all();

        $this->assertCount(2, $locales);
        $this->assertEquals('en', $locales[0]->code());
        $this->assertEquals('ru', $locales[1]->code());
    }
}
