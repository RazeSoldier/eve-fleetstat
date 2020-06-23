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
use RuntimeException;
use Seat\Eseye\Containers\EsiResponse;

class Corporation
{
    private EsiResponse $info;

    private function __construct(EsiResponse $info)
    {
        $this->info = $info;
    }

    public static function makeFromId(int $datasource, int $id): self
    {
        $info = Cache::remember("eve-$datasource-corp-info:$id", 86400, function () use ($datasource, $id) {
            return app(EsiClient::class)
                ->request($datasource, 'get', "/corporations/$id");
        });
        return new self($info);
    }

    public function __get(string $name): string
    {
        if (isset($this->info->$name)) {
            return $this->info->$name;
        } else {
            throw new RuntimeException("$name property dose not exists");
        }
    }
}
