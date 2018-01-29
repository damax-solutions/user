<?php

declare(strict_types=1);

namespace Damax\User\Tests\Domain\Model;

use Damax\User\Domain\Model\Email;
use Damax\User\Domain\Model\Locale;
use Damax\User\Domain\Model\MobilePhone;
use Damax\User\Domain\Model\Name;
use Damax\User\Domain\Model\Password;
use Damax\User\Domain\Model\Timezone;
use Damax\User\Domain\Model\User;
use Ramsey\Uuid\Uuid;

class JohnDoeUser extends User
{
    public function __construct()
    {
        $id = Uuid::fromString('ce08c4e8-d9eb-435b-9eab-edc252b450e1');
        $email = Email::fromString('john.doe@domain.abc');
        $mobilePhone = MobilePhone::fromNumber(123);
        $password = Password::valid3Months('qwerty', 'XYZ');
        $name = Name::fromArray(['first_name' => 'John', 'last_name' => 'Doe']);
        $timezone = Timezone::fromId('Europe/Riga');
        $locale = Locale::fromCode('ru');

        parent::__construct($id, $email, $mobilePhone, $password, $name, $timezone, $locale);
    }
}
