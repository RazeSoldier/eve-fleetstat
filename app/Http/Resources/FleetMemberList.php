<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FleetMemberList extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $json = json_decode($this->fleet_data, true);

        $arr = [];
        foreach ($json as $k => $v) {
            $arr[] = [
                'name' => $k,
                'corpName' => $v['corp'],
                'allianceName' => $v['alliance'],
            ];
        }
        return $arr;
    }
}
