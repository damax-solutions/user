<?php

declare(strict_types=1);

namespace Damax\User\Domain\Model;

use Assert\Assert;
use Damax\User\Domain\Configuration;
use Damax\User\Domain\Password\Encoder;

class ConfigurableUserFactory implements UserFactory
{
    private $users;
    private $encoder;
    private $config;
    private $className;

    public function __construct(UserRepository $users, Encoder $encoder, Configuration $config, string $userClassName)
    {
        $this->users = $users;
        $this->encoder = $encoder;
        $this->config = $config;
        $this->className = $userClassName;
    }

    public function create($data, User $creator = null): User
    {
        Assert::that($data)
            ->keyIsset('email')
            ->keyIsset('mobile_phone')
            ->keyIsset('password')
        ;

        $email = Email::fromString($data['email']);
        $mobilePhone = MobilePhone::fromString($data['mobile_phone']);
        $password = $this->encoder->encode($data['password']);
        $name = Name::fromArray($data['name'] ?? null);

        if ($this->config->invalidatePassword()) {
            $password = $password->invalidate();
        }

        $className = $this->className;

        return new $className($this->users->nextId(), $email, $mobilePhone, $password, $name, $this->config->defaultTimezone(), $this->config->defaultLocale(), $creator);
    }
}
