<?php

use App\Http\Resources\FleetMemberList;
use App\Model\Fleet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('api')->post('/post-fleetmember-list', 'Api\PostFleetMemberList@index');
Route::middleware('api')->get('/get-fleetmember-list/{hash}', function ($hash) {
    return new FleetMemberList(Fleet::find($hash));
});
