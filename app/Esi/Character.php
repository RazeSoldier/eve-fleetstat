<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

namespace App\Esi;

use Illuminate\Support\Facades\Cache;
use LogicException;
use RuntimeException;
use Seat\Eseye\Containers\EsiResponse;

class Character
{
    private string $name;
    private int $id;
    private EsiResponse $info;
    private int $datasource;

    private function __construct(string $name, int $id, int $datasource)
    {
        $this->name = $name;
        $this->id = $id;
        $this->datasource = $datasource;
        $this->info = self::fetchInfo($this->datasource, $this->id);
    }

    public static function makeFromName(int $datasource, string $name): self
    {
        if ($datasource !== 1 && $datasource !== 2) {
            throw new LogicException("Unknown datasource: $datasource");
        }
        $id = Cache::rememberForever("eve-$datasource-character-id:$name", function () use ($datasource, $name) {
            if ($datasource === 1) {
                return app(EsiClient::class)
                    ->request($datasource, 'get', '/search/', [
                        'search' => $name,
                        'categories' => 'character',
                        'strict' => 'true',
                    ])->character[0];
            }
            $resp = app(EsiClient::class)
                ->request($datasource, 'get', '/search/', [
                    'search' => mb_strlen($name) < 3 ? ' ' . $name : $name,
                    'categories' => 'character',
                    'strict' => 'false',
                ])->character;
            if (count($resp) === 1) {
                return $resp[0];
            }
            foreach ($resp as $id) {
                $info = self::fetchInfo($datasource, $id);
                if ($info->name === $name) {
                    return $id;
                }
            }
            throw new RuntimeException("Failed to make instance (datasource: $datasource, name: $name)");
        });
        return new self($name, $id, $datasource);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function getCorporation(): Corporation
    {
        return Corporation::makeFromId($this->datasource, $this->corporation_id);
    }

    public function getAlliance():? Alliance
    {
        if (isset($this->info->alliance_id)) {
            return Alliance::makeFromId($this->datasource, $this->info->alliance_id);
        }
        return null;
    }

    public function __get(string $name): string
    {
        if (isset($this->info->$name)) {
            return $this->info->$name;
        } else {
            throw new RuntimeException("$name property dose not exists");
        }
    }

    private static function fetchInfo(int $datasource, int $id): EsiResponse
    {
        return Cache::remember("eve-$datasource-character-info:$id", 86400, function () use ($datasource, $id) {
            return app(EsiClient::class)->request($datasource, 'get', "/characters/$id/");
        });
    }
}
