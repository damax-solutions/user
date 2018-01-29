<?php

declare(strict_types=1);

namespace Damax\User\Tests\Application\Service;

use Damax\User\Application\Dto\Assembler;
use Damax\User\Application\Dto\UserLoginDto;
use Damax\User\Application\Service\UserLoginService;
use Damax\User\Domain\Model\LoginHistory;
use Damax\User\Domain\Model\LoginHistoryRepository;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class UserLoginServiceTest extends TestCase
{
    /**
     * @var LoginHistoryRepository|PHPUnit_Framework_MockObject_MockObject
     */
    private $logins;

    /**
     * @var Assembler|PHPUnit_Framework_MockObject_MockObject
     */
    private $assembler;

    /**
     * @var UserLoginService
     */
    private $service;

    protected function setUp()
    {
        $this->logins = $this->createMock(LoginHistoryRepository::class);
        $this->assembler = $this->createMock(Assembler::class);
        $this->service = new UserLoginService($this->logins, $this->assembler);
    }

    /**
     * @test
     */
    public function it_fetches_range()
    {
        $login = $this->createMock(LoginHistory::class);

        $this->logins
            ->expects($this->once())
            ->method('paginateByUserId')
            ->with('ce08c4e8-d9eb-435b-9eab-edc252b450e1')
            ->willReturn(new Pagerfanta(new ArrayAdapter(array_fill(0, 35, $login))))
        ;
        $this->assembler
            ->expects($this->exactly(10))
            ->method('toUserLoginDto')
        ;

        $items = $this->service
            ->fetchRange('ce08c4e8-d9eb-435b-9eab-edc252b450e1')
            ->setMaxPerPage(10)
        ;

        $this->assertCount(35, $items);
        $this->assertContainsOnlyInstancesOf(UserLoginDto::class, $array = iterator_to_array($items));
        $this->assertCount(10, $array);
    }
}
