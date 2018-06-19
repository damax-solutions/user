<?php

declare(strict_types=1);

namespace Damax\User\Tests\InMemory;

use Damax\User\InMemory\TimezoneRepository;
use PHPUnit\Framework\TestCase;

class TimezoneRepositoryTest extends TestCase
{
    /**
     * @var TimezoneRepository
     */
    private $repository;

    protected function setUp()
    {
        $this->repository = new TimezoneRepository(['Europe/Riga', 'Europe/London']);
    }

    /**
     * @test
     */
    public function it_fetches_by_id()
    {
        $this->assertEquals('Europe/Riga', $this->repository->byId('Europe/Riga')->id());
        $this->assertNull($this->repository->byId('Europe/Moscow'));
    }

    /**
     * @test
     */
    public function it_fetches_all()
    {
        $timezones = $this->repository->all();

        $this->assertCount(2, $timezones);
        $this->assertEquals('Europe/Riga', $timezones[0]->id());
        $this->assertEquals('Europe/London', $timezones[1]->id());
    }
}
