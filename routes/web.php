<?php

use Illuminate\Support\Facades\Route;
use App\Models\Account;
use App\Models\User;
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
    $user = User::find('9ac0f5a7-1c3c-47af-86be-3fcc0c9f4beb');
    $userService = new \App\Services\UserService();


//    $user->friendRequests()->syncWithoutDetaching(['9ac0f5a8-2aa8-40a2-b6a9-31a6fc7e0fb0']);
//    $userService->addFriend('9ac0f5a7-1c3c-47af-86be-3fcc0c9f4beb', User::find('9ac0f5a8-2c2c-4e5b-9f51-8ac97ccb42fe'));
    $friends = $user->friends;
    $friendRequests = $user->friendRequests;
    return response()->json($user);
});
