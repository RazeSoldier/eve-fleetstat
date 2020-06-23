<?php

namespace Tests\Esi;

use App\Esi\Character;
use App\Esi\EsiClient;
use Tests\TestCase;

/**
 * @covers \App\Esi\Character
 */
class CharacterTest extends TestCase
{
    public function testBasic()
    {
        $obj = Character::makeFromName(EsiClient::Tranquility, 'Starshine Morning');
        self::assertSame(2112309917, $obj->getId());
        self::assertSame('male', $obj->gender);

        $obj = Character::makeFromName(EsiClient::Serenity, '丶丘林洛斯');
        self::assertSame('2020-06-18T16:42:45Z', $obj->birthday);
        self::assertSame('male', $obj->gender);
    }

    /**
     * @covers \App\Esi\Character::getCorporation
     */
    public function testGetCorporation()
    {
        $obj = Character::makeFromName(EsiClient::Tranquility, 'Starshine Morning');
        self::assertSame('Levy Guards', $obj->getCorporation()->name);
    }

    /**
     * @covers \App\Esi\Character::getAlliance
     */
    public function testGetAlliance()
    {
        $obj = Character::makeFromName(EsiClient::Tranquility, 'Starshine Morning');
        self::assertSame('VENI VIDI VICI.', $obj->getAlliance()->name);
    }
}
