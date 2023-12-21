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
//   $userService = new \App\Services\UserService();
    $postService = new \App\Services\PostService();

//   $userService->sendFriendRequest('9ada94cb-a0a8-4545-a666-28e5b0de7ef6', '9ada94cb-9dd7-485c-9079-514c09349251');

//   $postService->createNew(
//       '9ae2f5b1-c026-4805-8c4d-5b1121616104',
//       'hello',
//       'hdwoaw.jpg'
//   );

//    $user = User::find('9ae31bda-d42b-4b19-873f-9e2b041fa519');
//    return response()->json($user->posts);

//    $request->session()->put('key', 'Hello');

    $user = \App\Models\User::find('9ae34d38-41a1-4a4d-9345-c32e678af5fd');

//    $request->cookie('test');
    $id = [
        'id' => '',
        'exp' => 5,
    ];

    $cookie = cookie('test', '092139812', 1);

    $posts = $postService->getPosts($user, 5, []);

//    dd($posts->items());

    return response()->json($posts->items())->cookie($cookie);

});

Route::get('/test', function (\Illuminate\Http\Request $request) {
    dd($request->cookie('test'));
});

