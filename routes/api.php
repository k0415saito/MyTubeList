<?php

use Illuminate\Http\Request;

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

// API

Route::group(["middleware" => "guest:api"], function () {
    Route::post("/auth/login", "API\Auth\LoginController@login");
    Route::post("/auth/register", "API\Auth\RegisterController@register");
});

Route::group(["middleware" => "auth:api"], function () {
    Route::post("/auth/logout", "API\Auth\LoginController@logout");

    Route::get("/ping", "API\Auth\LoginController@ping");

    Route::get("/video", "API\YouTubeController@getAllVideos");
    Route::post("/video", "API\YouTubeController@addVideoData");
    Route::put("/video", "API\YouTubeController@editVideoData");
    Route::delete("/video", "API\YouTubeController@deleteVideoData");

    Route::get("/playlist", "API\PlaylistController@getPlaylists");
    Route::get("/playlist/{id}", "API\PlaylistController@getVideos")->where('id', '[0-9]+');
    Route::post("/playlist", "API\PlaylistController@createPlaylist");
    Route::post("/playlist/add", "API\PlaylistController@addVideo");
    Route::put("/playlist", "API\PlaylistController@editTitle");
    Route::delete("/playlist", "API\PlaylistController@deletePlaylist");
    Route::delete("/playlist/delete", "API\PlaylistController@deleteVideo");
});