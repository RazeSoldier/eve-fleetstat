<?php

namespace Jobs;

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
        $this->assertSame('Levy Guards', json_decode($model->fleet_data, true)['Starshine Morning']);
    }
}
