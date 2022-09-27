<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CopyController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;

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


//copy
Route::get("api/copies", [CopyController::class, "showAll"]);
Route::get("api/copies/{id}", [CopyController::class, "show"]);
Route::put("api/copies/{id}", [CopyController::class, "update"]);
Route::delete("api/copies/{id}", [CopyController::class, "destroy"]);
Route::post("api/copies", [CopyController::class, "make"]);

Route::get("copy/new", [CopyController::class, "newView"]);
Route::get("copy/edit/{id}", [CopyController::class, "editView"]);
Route::get("copy/list", [CopyController::class, "listView"]);

//book
Route::get("api/books", [BookController::class, "showAll"]);
Route::get("api/books/{id}", [BookController::class, "show"]);
Route::put("api/books/{id}", [BookController::class, "update"]);
Route::delete("api/books/{id}", [BookController::class, "destroy"]);
Route::post("api/books", [BookController::class, "make"]);

Route::get("book/new", [BookController::class, "newView"]);
Route::get("book/edit/{id}", [BookController::class, "editView"]);
Route::get("book/list", [BookController::class, "listView"]);

//user
Route::get("api/users", [UserController::class, "showAll"]);
Route::get("api/users/{id}", [UserController::class, "show"]);
Route::put("api/users/{id}", [UserController::class, "update"]);
Route::delete("api/users/{id}", [UserController::class, "destroy"]);
Route::post("api/users", [UserController::class, "make"]);

Route::get("user/new", [UserController::class, "newView"]);
Route::get("user/edit/{id}", [UserController::class, "editView"]);
Route::get("user/list", [UserController::class, "listView"]);