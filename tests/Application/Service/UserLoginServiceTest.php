<?php

declare(strict_types=1);

namespace Damax\User\Tests\Application\Service;

use Damax\User\Application\Command\RecordLogin;
use Damax\User\Application\Dto\Assembler;
use Damax\User\Application\Dto\UserLoginDto;
use Damax\User\Application\Service\UserLoginService;
use Damax\User\Domain\Model\LoginHistory;
use Damax\User\Domain\Model\LoginHistoryRepository;
use Damax\User\Domain\Model\UserRepository;
use Damax\User\Tests\Domain\Model\JohnDoeUser;
use DateTimeInterface;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Ramsey\Uuid\Uuid;

class UserLoginServiceTest extends TestCase
{
    /**
     * @var UserRepository|PHPUnit_Framework_MockObject_MockObject
     */
    private $users;

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
        $this->users = $this->createMock(UserRepository::class);
        $this->logins = $this->createMock(LoginHistoryRepository::class);
        $this->assembler = $this->createMock(Assembler::class);
        $this->service = new UserLoginService($this->users, $this->logins, $this->assembler);
    }

    /**
     * @test
     */
    public function it_fetches_range_by_user()
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
            ->fetchRangeByUser('ce08c4e8-d9eb-435b-9eab-edc252b450e1')
            ->setMaxPerPage(10)
        ;

        $this->assertCount(35, $items);
        $this->assertContainsOnlyInstancesOf(UserLoginDto::class, $array = iterator_to_array($items));
        $this->assertCount(10, $array);
    }

    /**
     * @test
     */
    public function it_records_login()
    {
        $command = new RecordLogin();
        $command->userId = 'john.doe@domain.abc';
        $command->clientIp = '192.168.99.100';
        $command->serverIp = '192.168.99.1';
        $command->userAgent = 'Chrome';

        /** @var LoginHistory $login */
        $login = null;

        $this->users
            ->expects($this->once())
            ->method('byEmail')
            ->with('john.doe@domain.abc')
            ->willReturn($user = new JohnDoeUser())
        ;
        $this->logins
            ->expects($this->once())
            ->method('nextId')
            ->willReturn($id = Uuid::uuid4())
        ;
        $this->logins
            ->expects($this->once())
            ->method('save')
            ->willReturnCallback(function (LoginHistory $loginHistory) use (&$login) {
                $login = $loginHistory;
            })
        ;

        $this->service->recordLogin($command);

        $this->assertInstanceOf(LoginHistory::class, $login);
        $this->assertSame($id, $login->id());
        $this->assertSame($user, $login->user());
        $this->assertEquals('192.168.99.100', $login->clientIp());
        $this->assertEquals('192.168.99.1', $login->serverIp());
        $this->assertEquals('Chrome', $login->userAgent());
        $this->assertInstanceOf(DateTimeInterface::class, $login->createdAt());
    }
}
