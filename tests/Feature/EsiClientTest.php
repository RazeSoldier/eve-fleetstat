<?php

namespace Tests\Feature;

use App\Esi\EsiClient;
use Tests\TestCase;

/**
 * @covers \App\Esi\EsiClient
 */
class EsiClientTest extends TestCase
{
    public function testPublicData()
    {
        $esi = $this->createApplication()->make(EsiClient::class);
        $resp = $esi->request(EsiClient::Tranquility, 'get', '/characters/2112309917/');
        $this->assertSame('Starshine Morning', $resp->name);

        $resp = $esi->request(EsiClient::Serenity, 'get', '/characters/2112259363/');
        $this->assertSame('RazeSoldier', $resp->name);

        $resp = $esi->request(EsiClient::Serenity, 'get', '/search/', [
            'search' => 'RazeSoldier',
            'strict' => 'true',
            'categories' => 'character',
        ]);
        $this->assertSame(2112259363, $resp->character[0]);
    }
}
