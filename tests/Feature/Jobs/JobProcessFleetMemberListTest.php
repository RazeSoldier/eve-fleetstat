<?php

namespace Tests\Jobs;

use App\Jobs\JobProcessFleetMemberList;
use App\Model\Fleet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @covers \App\Jobs\JobProcessFleetMemberList
 */
class JobProcessFleetMemberListTest extends TestCase
{
    use RefreshDatabase;

    public function testBasic()
    {
        $model = new Fleet;
        $model->fleet_hash = 'test';
        JobProcessFleetMemberList::dispatchNow($model, 'tranquility', [
            'Starshine Morning',
        ]);
        $this->assertSame(['corp' => 'Levy Guards', 'alliance' => 'VENI VIDI VICI.'], json_decode($model->fleet_data, true)['Starshine Morning']);
    }

    public function testSerenity()
    {
        $model = new Fleet;
        $model->fleet_hash = 'test';
        JobProcessFleetMemberList::dispatchNow($model, 'serenity', [
            'A Guang',
            '丶丘林洛斯',
        ]);
        self::assertTrue(true);
    }
}
