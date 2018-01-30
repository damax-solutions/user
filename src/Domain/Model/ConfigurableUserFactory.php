<?php

declare(strict_types=1);

namespace Damax\User\Domain\Model;

use Assert\Assert;
use Damax\User\Domain\Configuration;
use Damax\User\Domain\Password\Encoder;

class ConfigurableUserFactory implements UserFactory
{
    private $idGenerator;
    private $encoder;
    private $config;

    public function __construct(IdGenerator $idGenerator, Encoder $encoder, Configuration $config)
    {
        $this->idGenerator = $idGenerator;
        $this->encoder = $encoder;
        $this->config = $config;
    }

    public function create($data): User
    {
        Assert::that($data)
            ->keyIsset('email')
            ->keyIsset('mobile_phone')
            ->keyIsset('password')
            ->keyIsset('name')
        ;

        $email = Email::fromString($data['email']);
        $mobilePhone = MobilePhone::fromString($data['mobile_phone']);
        $password = $this->encoder->encode($data['password']);
        $name = Name::fromArray($data['name']);

        if ($this->config->invalidatePassword()) {
            $password = $password->invalidate();
        }

        return new User($this->idGenerator->nextId(), $email, $mobilePhone, $password, $name, $this->config->defaultTimezone(), $this->config->defaultLocale());
    }
}
