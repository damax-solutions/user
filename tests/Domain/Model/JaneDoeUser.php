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

class JaneDoeUser extends User
{
    public function __construct()
    {
        $id = Uuid::fromString('02158a54-0510-11e8-a654-005056806fb2');
        $email = Email::fromString('jane.doe@domain.abc');
        $mobilePhone = MobilePhone::fromNumber(456);
        $password = Password::valid3Months('qwerty', 'ZYZ');
        $name = Name::fromArray(['first_name' => 'Jane', 'last_name' => 'Doe']);
        $timezone = Timezone::fromId('Europe/Riga');
        $locale = Locale::fromCode('lv');

        parent::__construct($id, $email, $mobilePhone, $password, $name, $timezone, $locale);
    }
}
