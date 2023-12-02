<?php

use Illuminate\Support\Facades\Route;
use App\Models\Account;
use App\Models\User;
use App\Models\Friend;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $user = User::find('9ac0c2cd-0e2d-4850-b48b-82575e6f6b52');
    $friends = $user->friends()->paginate(1);
    return response()->json($friends);
});
