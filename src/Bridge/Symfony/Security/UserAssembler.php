<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Security;

use Damax\User\Domain\Model\User as UserModel;
use Symfony\Component\Security\Core\User\UserInterface;

interface UserAssembler
{
    public function assemble(UserModel $user): UserInterface;
}
