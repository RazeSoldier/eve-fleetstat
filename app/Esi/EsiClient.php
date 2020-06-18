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

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiResponse;
use Seat\Eseye\Eseye;
use Seat\Eseye\Log\NullLogger;

class EsiClient
{
    private Eseye $esi;
    private Configuration $config;

    private const CLIENT_CONFIG = [
        1 => [
            'datasource' => 'tranquility',
            'esi_host' => 'esi.evetech.net',
        ],
        2 => [
            'datasource' => 'serenity',
            'esi_host' => 'esi.evepc.163.com',
        ],
    ];

    public const Tranquility = 1;

    public const Serenity = 2;

    public function __construct()
    {
        $this->config = Configuration::getInstance();
        $this->config->logger = NullLogger::class; // Disable logging
        $this->config->cache = NullCache::class; // Disable cache
        $this->esi = new Eseye;
    }

    public function request(int $datasource, string $method, string $url, array $querystring = []) : EsiResponse
    {
        if ($datasource !== 1 && $datasource !== 2) {
            throw new \LogicException("Unknown datasource: $datasource");
        }
        foreach (self::CLIENT_CONFIG[$datasource] as $k => $v) {
            $this->config->$k = $v;
        }
        if ($querystring !== null) {
            $this->esi->setQueryString($querystring);
        }
        $resp = $this->esi->invoke($method, $url);
        $this->esi->cleanupRequestData();
        return $resp;
    }
}
