<?php

declare(strict_types=1);

namespace Damax\User\Tests\Bridge\Symfony\Security;

use Damax\User\Bridge\Symfony\Security\User;
use IntlDateFormatter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\User\User as SymfonyUser;

class UserTest extends TestCase
{
    /**
     * @var User
     */
    private $user;

    protected function setUp()
    {
        $this->user = (new UserFactory())->create('abc', ['ROLE_ADMIN']);
    }

    /**
     * @test
     */
    public function it_retrieves_values()
    {
        $this->assertEquals('abc', $this->user->getId());
        $this->assertEquals('123', $this->user->getUsername());
        $this->assertEquals(['ROLE_ADMIN', 'ROLE_MEMBER'], $this->user->getRoles());
        $this->assertEquals('qwerty', $this->user->getPassword());
        $this->assertEquals('XYZ', $this->user->getSalt());
        $this->assertTrue($this->user->isAccountNonExpired());
        $this->assertTrue($this->user->isAccountNonLocked());
        $this->assertTrue($this->user->isCredentialsNonExpired());
        $this->assertTrue($this->user->isEnabled());
        $this->assertEquals('Europe/Riga', $this->user->getTimezone());
        $this->assertEquals('ru', $this->user->getLocale());
    }

    /**
     * @test
     */
    public function it_erases_credentials()
    {
        $this->user->eraseCredentials();

        // $this->assertEmpty($this->user->getPassword());
        $this->assertEmpty($this->user->getSalt());
    }

    /**
     * @test
     */
    public function it_serializes_and_unserializes_user()
    {
        $this->assertEquals($this->user, unserialize(serialize($this->user)));
    }

    /**
     * @test
     */
    public function it_retrieves_date_formatter()
    {
        $formatter = $this->user->getDateFormatter(IntlDateFormatter::LONG, IntlDateFormatter::SHORT);

        $this->assertEquals('ru', $formatter->getLocale());
        $this->assertEquals('Europe/Riga', $formatter->getTimeZone()->getID());
        $this->assertEquals(IntlDateFormatter::LONG, $formatter->getDateType());
        $this->assertEquals(IntlDateFormatter::SHORT, $formatter->getTimeType());
    }

    /**
     * @test
     */
    public function it_compares_to_users_for_equality()
    {
        $factory = new UserFactory();

        $user1 = $factory->create('abc', ['ROLE_ADMIN']);
        $user2 = $factory->create('def', ['ROLE_ADMIN']);

        $this->assertTrue($this->user->isEqualTo($user1));
        $this->assertFalse($this->user->isEqualTo($user2));
        $this->assertFalse($this->user->isEqualTo(new SymfonyUser('abc', 'qwerty')));
    }
}
