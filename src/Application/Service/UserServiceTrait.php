<?php

declare(strict_types=1);

namespace Damax\User\Application\Service;

use Damax\User\Application\Exception\UserNotFound;
use Damax\User\Domain\Model\Email;
use Damax\User\Domain\Model\MobilePhone;
use Damax\User\Domain\Model\UserRepository;
use Ramsey\Uuid\Uuid;

trait UserServiceTrait
{
    /**
     * @var UserRepository
     */
    private $users;

    /**
     * @throws UserNotFound
     */
    private function getUser(string $id)
    {
        if (filter_var($id, FILTER_VALIDATE_EMAIL)) {
            if (null === $user = $this->users->byEmail(Email::fromString($id))) {
                throw UserNotFound::byEmail($id);
            }
        } elseif (Uuid::isValid($id)) {
            if (null === $user = $this->users->byId(Uuid::fromString($id))) {
                throw UserNotFound::byId($id);
            }
        } else {
            if (null === $user = $this->users->byMobilePhone(MobilePhone::fromString($id))) {
                throw UserNotFound::byMobilePhone($id);
            }
        }

        return $user;
    }
}
