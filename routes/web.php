<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@welcome');

Route::post('/webhook', 'PollsController@webHook');

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::resource('slacks', 'SlacksController')->except(['index']);
});

Route::resource('watermark', 'WatermarkController')->only(['index', 'store']);

Route::get('alexa', 'AlexaController@get');
Route::post('alexa', 'AlexaController@set');
