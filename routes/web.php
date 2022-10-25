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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth.admin'])->group(function () {
    Route::apiResource('/api/copies', CopyController::class);
    Route::apiResource('/api/books', BookController::class);
    Route::apiResource('/api/users', UserController::class);

    //view
    Route::get('/book/new', [BookController::class, 'newView']);
    Route::get('/book/edit/{id}', [BookController::class, 'editView']);
    Route::get('/book/list', [BookController::class, 'listView']);
    Route::get('/user/new', [UserController::class, 'newView']);
    Route::get('/user/edit/{id}', [UserController::class, 'editView']);
    Route::get('/user/list', [UserController::class, 'listView']);
});

Route::middleware(['auth.basic'])->group(function () {
    Route::apiResource('/api/copies', CopyController::class);
    Route::apiResource('/api/books', BookController::class);
    Route::apiResource('/api/users', UserController::class);

    //view
    Route::get('/copy/new', [CopyController::class, 'newView']);
    Route::get('/copy/edit/{id}', [CopyController::class, 'editView']);
    Route::get('/copy/list', [CopyController::class, 'listView']);
});

require __DIR__.'/auth.php';
