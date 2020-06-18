<?php

use App\Model\Fleet;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::view('/', 'mainpage');

Route::get('/view/{fleet:fleet_hash}', function (Fleet $fleet) {
    return view('view-fleet', [
        'fleetHash' => $fleet->fleet_hash,
        'isDone' => $fleet->fleet_done === 1 ? 'true' : 'false',
    ]);
});
