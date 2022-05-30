<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', [App\Http\Controllers\MoviesController::class, 'getAllMovies'])->name("home");


Route::prefix('dashboard')->middleware(['auth'])->group(function () {
    Route::get('branch', [App\Http\Controllers\Dashboard\UserController::class, 'myCinemas'])->name('dashboard.branch');

    Route::get('see_movie', [App\Http\Controllers\Dashboard\UserController::class, 'moviesInCinema'])->name('dashboard.cinema.movies');
    Route::get('create_movie', [App\Http\Controllers\Dashboard\UserController::class, 'createMovie'])->name('create_movie');
    Route::post('create_movie', [App\Http\Controllers\Dashboard\UserController::class, 'createMovieSubmit'])->name('create_movie.submit');
	
});