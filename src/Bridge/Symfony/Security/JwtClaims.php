<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Security;

use Damax\Bundle\ApiAuthBundle\Jwt\Claims;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;

class JwtClaims implements Claims
{
    /**
     * @throws UnsupportedUserException
     */
    public function resolve(UserInterface $user): array
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('User of type "%s" is not supported.', get_class($user)));
        }

        return [
            self::LOCALE => $user->getLocale(),
            self::TIMEZONE => $user->getTimezone(),
        ];
    }
}
