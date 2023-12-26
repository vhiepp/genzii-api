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


Route::get('/', function (\Illuminate\Http\Request $request) {
    $notifiService = new \App\Services\Notifications\NotificationService();

    $user = User::find('9aea68a4-9a59-4691-9873-623cc80fd685');
    dd($notifiService->getOldNotifiListForUser($user)->toArray());
});
