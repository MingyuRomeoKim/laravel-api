<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('v1')->group(function () {
    Route::prefix('tistory')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\TistoryController::class, 'index'])->name('tistory.index');
        Route::get('/accessToken', [\App\Http\Controllers\Api\TistoryController::class, 'accessToken'])->name('tistory.accessToken');
    });

    Route::prefix('naver')->group(function () {
        Route::get('/{keyword?}', [\App\Http\Controllers\Api\NaverController::class, 'index'])->name('naver.index');
    });

    Route::prefix('youtube')->group(function () {
        Route::get('/channel/{channelName}', [\App\Http\Controllers\Api\YoutubeController::class, 'getChannelId'])->name('youtube.channelId');
        Route::get('/channel/{channelId}/videos', [\App\Http\Controllers\Api\YoutubeController::class, 'getVideoList'])->name('youtube.videoList');
        Route::get('/video/{videoId}', [\App\Http\Controllers\Api\YoutubeController::class, 'getVideoDetail'])->name('youtube.videoDetail');
    });
});
