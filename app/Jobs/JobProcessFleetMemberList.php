<?php

namespace App\Jobs;

use App\Esi\EsiClient;
use App\Model\Fleet;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Seat\Eseye\Containers\EsiResponse;

class JobProcessFleetMemberList implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        $esi = app(EsiClient::class);
        $server = $this->server === 'tranquility' ? 1 : 2;
        $memberMap = [];
        foreach ($this->memberList as $member) {
            $uid = $this->getUidByName($member, $server);
            $info = $this->getCharacterInfoByUid($uid, $server);
            $memberMap[$member] = [
                'corp' => $this->getCorpNameById($info->corporation_id, $server),
                'alliance' => isset($info->alliance_id) ? $this->getAllianceNameById($info->alliance_id, $server) : null,
            ];
        }
        $this->fleet->fleet_data = json_encode($memberMap);
        $this->fleet->fleet_done = 1;
        $this->fleet->save();
    }

    private function getUidByName(string $name, int $server)
    {
        return Cache::rememberForever("eve-character-id:$name", function () use ($name, $server) {
            return app(EsiClient::class)
                ->request($server, 'get', '/search/', [
                    'search' => $name,
                    'strict' => 'true',
                    'categories' => 'character',
                ])->character[0];
        });
    }

    private function getCharacterInfoByUid(int $uid, int $server) : EsiResponse
    {
        return Cache::rememberForever("eve-character-info:$uid", function () use ($uid, $server){
            return app(EsiClient::class)->request($server, 'get', "/characters/$uid/");
        });
    }

    private function getCorpNameById(int $corpId, int $server) : string
    {
        return Cache::rememberForever("eve-corp-id:$corpId", function () use ($corpId, $server) {
            return app(EsiClient::class)
                ->request($server, 'get', "/corporations/$corpId")->name;
        });
    }

    private function getAllianceNameById(int $allianceId, int $server) : string
    {
        return Cache::rememberForever("eve-alliance-name:$allianceId", function () use ($allianceId, $server) {
            return app(EsiClient::class)
                ->request($server, 'get', "/alliances/$allianceId/")->name;
        });
    }
}
