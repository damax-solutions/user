<?php

declare(strict_types=1);

namespace Damax\User\Tests\Bridge\Symfony\Security;

use Damax\User\Bridge\Symfony\Security\UserChecker;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserCheckerTest extends TestCase
{
    /**
     * @test
     */
    public function it_has_no_credentials_expiration_check()
    {
        /** @var UserInterface|PHPUnit_Framework_MockObject_MockObject $user */
        $user = $this->createMock(AdvancedUserInterface::class);

        $user->expects($this->never())->method('isCredentialsNonExpired');

        (new UserChecker())->checkPostAuth($user);
    }
}
