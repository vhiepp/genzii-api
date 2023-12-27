<?php

use App\Models\Post;
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
//    return '123';
    $count = 0;
    $posts = Post::where('status', 'showing')->withCount('media')->orderBy('media_count', 'asc')->limit(10)->get();
    $ids = [];
    foreach ($posts as $post) {
        if ($post->media->count() == 0) {
            array_push($ids, $post->id);
            $count++;
        }
    }
    Post::whereIn('id', $ids)->update(['status' => 'deleted']);

    return "ok " . $count;
});
