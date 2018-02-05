<?php

declare(strict_types=1);

namespace Damax\User\Tests\Bridge\Symfony\Security;

use Damax\User\Bridge\Symfony\Security\RoleUserAssembler;
use Damax\User\Bridge\Symfony\Security\User;
use Damax\User\Tests\Domain\Model\AdminRole;
use Damax\User\Tests\Domain\Model\JohnDoeUser;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Role\Role;

class RoleUserAssemblerTest extends TestCase
{
    /**
     * @test
     */
    public function it_assembles_user()
    {
        $johndoe = new JohnDoeUser();
        $johndoe->assignRole(new AdminRole());

        /** @var User $user */
        $user = (new RoleUserAssembler())->assemble($johndoe);

        $this->assertEquals('ce08c4e8-d9eb-435b-9eab-edc252b450e1', $user->getId());
        $this->assertEquals('123', $user->getUsername());
        $this->assertEquals([new Role('ROLE_USER_CREATE'), new Role('ROLE_USER_EDIT'), new Role('ROLE_USER_DELETE'), 'ROLE_USER'], $user->getRoles());
        $this->assertEquals('qwerty', $user->getPassword());
        $this->assertEquals('XYZ', $user->getSalt());
        $this->assertEquals('Europe/Riga', $user->getTimezone());
        $this->assertEquals('ru', $user->getLocale());
        $this->assertTrue($user->isCredentialsNonExpired());
        $this->assertTrue($user->isEnabled());
    }
}
