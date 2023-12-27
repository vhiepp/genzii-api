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
    $count = 0;
    $posts = Post::withCount('media')->orderBy('media_count', 'asc')->limit(50)->get();
    foreach ($posts as $post) {
        if (count($post->media) == 0) {
            $post->media()->create([
                'file_url' => \Vhiepp\VNDataFaker\VNFaker::image(600, 800, $post->caption),
                'type' => 'image'
            ]);
            $count++;
        }
    }
    return "ok " . $count;
});
