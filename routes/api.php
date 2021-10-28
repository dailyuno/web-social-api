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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('youtube-play-lists', 'Api\YoutubePlayListController');
Route::resource('youtube-play-list-items', 'Api\YoutubePlayListItemController');
Route::resource('youtube-videos', 'Api\YoutubeVideoController');
Route::resource('youtube-video-thumbnails', 'Api\YoutubeVideoThumbnailController');
// Route::post('/youtube-play-list', 'Api\YoutubePlayListController')