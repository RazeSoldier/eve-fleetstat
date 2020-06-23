<?php

namespace App\Jobs;

use App\Esi\Character;
use App\Model\Fleet;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class JobProcessFleetMemberList implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 0;
    public $tries = 3;
    private Fleet $fleet;
    private string $server;
    private array $memberList;

    /**
     * Create a new job instance.
     *
     * @param Fleet $fleet
     * @param string $server
     * @param string[] $memberList
     */
    public function __construct(Fleet $fleet, string $server, array $memberList)
    {
        $this->fleet = $fleet;
        $this->server = $server;
        $this->memberList = $memberList;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $server = $this->server === 'tranquility' ? 1 : 2;
        $memberMap = [];
        foreach ($this->memberList as $member) {
            // Ignore empty line
            if (empty($member)) {
                continue;
            }
            $info = Character::makeFromName($server, $member);
            $memberMap[$member] = [
                'corp' => $info->getCorporation()->name,
                'alliance' => $info->getAlliance()->name ?? null,
            ];
        }
        $this->fleet->fleet_data = json_encode($memberMap);
        $this->fleet->fleet_done = 1;
        $this->fleet->save();
    }
}
