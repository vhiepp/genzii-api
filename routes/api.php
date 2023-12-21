<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\PostController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('api')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('sign-in', [AuthController::class, 'signInWithEmailPassword']);
        Route::post('sign-in-with-firebase', [AuthController::class, 'signInWithFirebase']);
        Route::get('sign-out', [AuthController::class, 'signOut']);
        Route::get('profile', [AuthController::class, 'profile']);
    });

    Route::prefix('user')->group(function () {
        Route::prefix('profile')->group(function () {
            Route::get('', [UserController::class, 'profile']);
            Route::prefix('{id}')->group(function () {
                Route::get('', [UserController::class, 'profileWithId']);
            });
        });
        Route::prefix('{id}')->group(function () {
            Route::get('', [UserController::class, 'profileWithId']);
            Route::get('profile', [UserController::class, 'profileWithId']);
            Route::get('posts', [PostController::class, 'getPostForUserId']);
        });
    });

    Route::prefix('friend')->group(function () {
        Route::get('', [FriendController::class, 'friends']);
        Route::post('', [FriendController::class, 'requestFriend']);
        Route::delete('',  [FriendController::class, 'cancelledFriend']);

        Route::prefix('request')->group(function () {
            Route::get('', [FriendController::class, 'friendRequests']);
            Route::delete('', [FriendController::class, 'cancelledFriendRequests']);
            Route::post('', [FriendController::class, 'agreedFriendRequests']);
        });
    });

    Route::prefix('follower')->group(function () {
        Route::get('', [FollowController::class, 'followers']);
    });

    Route::prefix('following')->group(function () {
        Route::get('', [FollowController::class, 'following']);
        Route::post('', [FollowController::class, 'followUser']);
        Route::delete('', [FollowController::class, 'cancelledFollowUser']);
    });

    Route::prefix('posts')->group(function () {
        Route::post('', [PostController::class, 'createNewPost']);
        Route::get('', [PostController::class, 'getPosts']);
//        Route::get('{id}', [PostController::class, 'getPostForUserId']);
    });

});
