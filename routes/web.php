<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
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
//   $userService = new \App\Services\UserService();

//   $userService->sendFriendRequest('9ada94cb-a0a8-4545-a666-28e5b0de7ef6', '9ada94cb-9dd7-485c-9079-514c09349251');

   $user = User::find('9adf1d74-0863-42c7-8c01-4d4475aec94e');
   $user->notifications;
   return response()->json($user);
});
