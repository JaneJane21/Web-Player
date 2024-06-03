<?php

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\TrackController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserGenreController;
use App\Models\UserGenre;
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



Route::get('/',[PageController::class,'welcome'])->name('welcome');

Route::get('login',[PageController::class,'login'])->name('login');
Route::get('reg',[PageController::class,'reg'])->name('reg');
Route::post('login/auth',[UserController::class,'auth'])->name('auth');
Route::post('reg/auth',[UserController::class,'reg'])->name('reg_save');
Route::get('user/logout',[UserController::class,'logout'])->name('logout');

Route::get('reg/genres',[PageController::class,'show_select_genres'])->name('show_select_genres');
Route::get('reg/genres/get',[GenreController::class,'index'])->name('get_genres');
Route::post('reg/genres/post',[GenreController::class,'store'])->name('post_genres');

Route::get('user/track',[PageController::class,'new_track'])->name('new_track');
Route::post('user/track/new',[TrackController::class,'store'])->name('store_track');
Route::post('user/album/new',[AlbumController::class,'store'])->name('store_album');

Route::get('tracks',[TrackController::class,'index'])->name('get_all_tracks');

Route::get('track/{id?}',[PageController::class,'track'])->name('track');

Route::post('track/get',[TrackController::class,'index_one'])->name('index_one');

Route::get('track/get/playlist',[PlaylistController::class,'index_for_song'])->name('getPlaylist');

Route::post('playlist/new/',[PlaylistController::class,'song_in_new_playlist'])->name('song_in_new_playlist');
Route::post('playlist/send/',[PlaylistController::class,'song_in_playlist'])->name('song_in_playlist');

Route::post('playlist/listen',[TrackController::class,'add_track_listen'])->name('add_track_listen');

Route::post('search_result',[PageController::class,'search'])->name('search');

Route::get('playlist/search_page',[PageController::class,'show_search_page'])->name('show_search_page');

Route::get('user/artist/profile',[PageController::class,'artist_profile'])->name('artist_profile');
Route::get('user/artist/profile/tracks',[TrackController::class,'index_my_tracks'])->name('get_my_tracks');

Route::post('user/artist/profile/tracks/hide',[TrackController::class,'application_hide_track'])->name('application_hide_track');
Route::post('user/artist/profile/tracks/delete',[TrackController::class,'application_delete_track'])->name('application_delete_track');

Route::get('user/playlist/my_beats',[PageController::class,'my_beats'])->name('my_beats');

Route::get('user/user/profile',[PageController::class,'user_profile'])->name('user_profile');
Route::get('user/user/profile/favs',[UserGenreController::class,'user_favs'])->name('user_favs');

