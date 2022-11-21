<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\CopyController;
use App\Http\Controllers\LendingController;
use App\Http\Controllers\UserController;
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

//ADMIN
Route::middleware( ['admin'])->group(function () {
    //books
    Route::get('/api/books', [BookController::class, 'index']);
    Route::post('/api/books', [BookController::class, 'store']);
    Route::get('/api/books/{id}', [BookController::class, 'show']);
    Route::put('/api/books/{id}', [BookController::class, 'update']);
    Route::delete('/api/books/{id}', [BookController::class, 'destroy']);
    //copies
    Route::apiResource('/api/copies', CopyController::class);
    //view - copy
    Route::get('/copy/new', [CopyController::class, 'newView']);
    Route::get('/copy/edit/{id}', [CopyController::class, 'editView']);
    Route::get('/copy/list', [CopyController::class, 'listView']);
    //// selects
    // Egy adott című könyv példányait listázd ki!
    Route::get('/api/book_copies/{title}', [BookController::class, 'bookCopies']);
    // Hány darab példány van egy adott című könyvből?
    Route::get('/api/book_copy_count/{title}', [CopyController::class, 'bookCopyCount']);
    // Add meg a keménykötésű példányokat szerzővel és címmel! +
    Route::get('/api/hard_books/{isHard}', [BookController::class, 'hardBooks']);
    // Bizonyos évben kiadott példányok névvel és címmel kiíratása.
    Route::get('/api/books_published_in/{year}', [BookController::class, 'booksPublishedIn']);
    // Raktárban lévő példányok száma.
    Route::get('/api/in_storage_count', [BookController::class, 'inStorageCount']);
    // Bizonyos évben kiadott, bizonyos könyv raktárban lévő darabjainak a száma.
    Route::get('/api/book_year_in_storage_count/{id}/{year}', [BookController::class, 'bookYearInStorageCount']);
    // Adott könyvhöz (book_id) tartozó példányok kölcsönzési adatai
    Route::get('/api/book_lendings/{id}', [BookController::class, 'bookLendings']);
});

//SIMPLE USER
Route::middleware(['auth.basic'])->group(function () {
    // Route::get('/api/books', [BookController::class, 'index']);
    //user   
    Route::apiResource('/api/users', UserController::class);
    Route::patch('/api/users/password/{id}', [UserController::class, 'updatePassword']);
    //queries
    ////selects
    // Az eddig kikölcsönzött könyvek listája a bejelentkezett felhasználó által 
    Route::get('/api/user_lendings', [LendingController::class, 'userLendingsList']);
    // Az eddig kikölcsönzött könyvek listája a bejelentkezett felhasználó által
    Route::get('/api/user_lendings_extra', [LendingController::class, 'userLendingsListExtra']);
    // Hányszor kölcsönzött ki könyvet eddig a bejelentkezett felhasználó
    Route::get('/api/user_lendings_count', [LendingController::class, 'userLendingsCount']);
    // Hányszor kölcsönzött ki könyvet eddig a bejelentkezett felhasználó (vizsgáljuk, ha ugyanazt a könyvet többször is kikölcsönözte)?
    Route::get('/api/user_lendings_count_extra', [LendingController::class, 'userLendingsCountExtra']);
});
//csak a tesztelés miatt van "kint"
Route::patch('/api/users/password/{id}', [UserController::class, 'updatePassword']);
Route::apiResource('/api/copies', CopyController::class);
Route::get('/api/lendings', [LendingController::class, 'index']); 
Route::get('/api/lendings/{user_id}/{copy_id}/{start}', [LendingController::class, 'show']);
Route::put('/api/lendings/{user_id}/{copy_id}/{start}', [LendingController::class, 'update']);
Route::patch('/api/lendings/{user_id}/{copy_id}/{start}', [LendingController::class, 'update']);
Route::post('/api/lendings', [LendingController::class, 'store']);
Route::delete('/api/lendings/{user_id}/{copy_id}/{start}', [LendingController::class, 'destroy']);

require __DIR__.'/auth.php';
