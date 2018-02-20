<?php

declare(strict_types=1);

namespace Damax\User\Tests\Domain\Model;

use Damax\User\Domain\Model\MobilePhone;
use PHPUnit\Framework\TestCase;

class MobilePhoneTest extends TestCase
{
    /**
     * @test
     */
    public function it_compares_mobile_phones()
    {
        $mobile1 = MobilePhone::fromNumber(123);
        $mobile2 = MobilePhone::fromNumber(456);

        $this->assertFalse($mobile1->sameAs($mobile2));
        $this->assertTrue($mobile1->sameAs(MobilePhone::fromNumber(123)));
    }

    /**
     * @test
     */
    public function it_confirms_mobile_phone()
    {
        $mobile = MobilePhone::fromNumber(123);
        $this->assertFalse($mobile->confirmed());

        $confirmed = $mobile->confirm();
        $this->assertTrue($confirmed->confirmed());

        $this->assertNotSame($mobile, $confirmed);
    }

    /**
     * @test
     */
    public function it_converts_to_string()
    {
        $mobile = MobilePhone::fromNumber(123);

        $this->assertEquals('+123', (string) $mobile);
    }

    /**
     * @test
     */
    public function it_creates_from_string()
    {
        $mobile = MobilePhone::fromString('+123');

        $this->assertEquals(123, $mobile->number());
    }
}
