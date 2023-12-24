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
    dd(env('SERVER_IMAGE_URL', false));
});

Route::get('/test', function (\Illuminate\Http\Request $request) {
    dd($request->cookie('test'));
});

