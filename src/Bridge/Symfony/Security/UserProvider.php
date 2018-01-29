<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Security;

use Damax\User\Domain\Model\Email;
use Damax\User\Domain\Model\MobilePhone;
use Damax\User\Domain\Model\UserRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    private $repository;
    private $assembler;

    public function __construct(UserRepository $repository, UserAssembler $assembler)
    {
        $this->repository = $repository;
        $this->assembler = $assembler;
    }

    public function loadUserByUsername($username): UserInterface
    {
        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $user = $this->repository->byEmail(Email::fromString($username));
        } else {
            $user = $this->repository->byMobilePhone(MobilePhone::fromString($username));
        }

        if (null === $user) {
            throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username));
        }

        return $this->assembler->assemble($user);
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('User of type "%s" is not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class): bool
    {
        return User::class === $class;
    }
}
