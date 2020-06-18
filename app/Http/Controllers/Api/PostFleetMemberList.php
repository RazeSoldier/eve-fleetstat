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

namespace App\Http\Controllers\Api;

use App\Jobs\JobProcessFleetMemberList;
use App\Model\Fleet;
use Illuminate\Http\Request;

/**
 * POST /api/post-fleetmember-list
 */
class PostFleetMemberList
{
    public function index(Request $request)
    {
        $server = $request->post('server');
        $memberList = explode("\n", $request->post('list'));
        $model = new Fleet;
        $model->fleet_hash = substr(
            hash('sha256', date('c') . microtime(), false),
            0, 10
        );
        $model->save();
        // Put the Fleet to the job
        JobProcessFleetMemberList::dispatch($model, $server, $memberList);
        return response()->json([
            'fleet_hash' => $model->fleet_hash,
        ]);
    }
}
