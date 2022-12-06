<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\CopyController;
use App\Http\Controllers\LendingController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;
use App\Mail\DemoMail;
use App\Http\Controllers\MailController;
use App\Http\Controllers\FileController;
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
    //users
    Route::apiResource('/api/users', UserController::class);
    Route::patch('/api/users/password/{id}', [UserController::class, 'updatePassword']);
    //books
    Route::post('/api/books', [BookController::class, 'store']);
    Route::put('/api/books/{id}', [BookController::class, 'update']);
    Route::delete('/api/books/{id}', [BookController::class, 'destroy']);
    //copies
    Route::apiResource('/api/copies', CopyController::class);
    //view - copy
    Route::get('/copy/new', [CopyController::class, 'newView']);
    Route::get('/copy/edit/{id}', [CopyController::class, 'editView']);
    //reservations
    Route::post('/api/reservations', [ReservationController::class, 'store']);
    Route::put('/api/reservations/{copy}/{book}/{start}', [ReservationController::class, 'update']);
    Route::delete('/api/reservations/{copy}/{book}/{start}', [ReservationController::class, 'destroy']);
    //lendings
    Route::put('/api/lendings/{user_id}/{copy_id}/{start}', [LendingController::class, 'update']);
    Route::patch('/api/lendings/{user_id}/{copy_id}/{start}', [LendingController::class, 'update']);
    Route::post('/api/lendings', [LendingController::class, 'store']);
    Route::delete('/api/lendings/{user_id}/{copy_id}/{start}', [LendingController::class, 'destroy']);
    //deletes
    Route::delete('/api/delete_old_reservations', [ReservationController::class, 'deleteOldReservations']);
    
});

// LIBRARIAN
Route::middleware( ['librarian'])->group(function () {
    Route::get('/api/reservations', [ReservationController::class, 'index']);
    Route::get('/api/lendings', [LendingController::class, 'index']);
    Route::get('/api/lendings/{user_id}/{copy_id}/{start}', [LendingController::class, 'show']);
    Route::get('/api/reservations/{copy}/{book}/{start}', [ReservationController::class, 'show']);
    //// selects
    // Raktárban lévő példányok száma.
    Route::get('/api/in_storage_count', [BookController::class, 'inStorageCount']);
    // Bizonyos évben kiadott, bizonyos könyv raktárban lévő darabjainak a száma.
    Route::get('/api/book_year_in_storage_count/{id}/{year}', [BookController::class, 'bookYearInStorageCount']);
    // Adott könyvhöz (book_id) tartozó példányok kölcsönzési adatai
    Route::get('/api/book_lendings/{id}', [BookController::class, 'bookLendings']);
});

//SIMPLE USER
Route::middleware(['auth.basic'])->group(function () {
    Route::get('/copy/list', [CopyController::class, 'listView']);
    Route::get('/api/books', [BookController::class, 'index']);
    Route::get('/api/books/{id}', [BookController::class, 'show']);
    ////selects
    // Az eddig kikölcsönzött könyvek listája a bejelentkezett felhasználó által 
    Route::get('/api/user_lendings', [LendingController::class, 'userLendingsList']);
    // Az eddig kikölcsönzött könyvek listája a bejelentkezett felhasználó által
    Route::get('/api/user_lendings_extra', [LendingController::class, 'userLendingsListExtra']);
    // Hányszor kölcsönzött ki könyvet eddig a bejelentkezett felhasználó
    Route::get('/api/user_lendings_count', [LendingController::class, 'userLendingsCount']);
    // Hányszor kölcsönzött ki könyvet eddig a bejelentkezett felhasználó (vizsgáljuk, ha ugyanazt a könyvet többször is kikölcsönözte)?
    Route::get('/api/user_lendings_count_extra', [LendingController::class, 'userLendingsCountExtra']);
    // Hány darab előjegyzése van a bejelentkezett felhasználónak?
    Route::get('/api/user_reservations_count', [ReservationController::class, 'userReservationsCount']);
    // Csoportosítsd szerzőnként a könyveket (nem példányokat) a szerzők ABC szerinti növekvő sorrendjében!
    Route::get('/api/books_by_author', [BookController::class, 'booksByAuthor']);

    // Egy adott című könyv példányait listázd ki!
    Route::get('/api/book_copies/{title}', [BookController::class, 'bookCopies']);
    // Hány darab példány van egy adott című könyvből?
    Route::get('/api/book_copy_count/{title}', [CopyController::class, 'bookCopyCount']);
    // Add meg a keménykötésű példányokat szerzővel és címmel! +
    Route::get('/api/hard_books/{isHard}', [BookController::class, 'hardBooks']);
    // Bizonyos évben kiadott példányok névvel és címmel kiíratása.
    Route::get('/api/books_published_in/{year}', [BookController::class, 'booksPublishedIn']);
    //Határozd meg a könyvtár nyilvántartásában legalább x könyvvel rendelkező szerzőket!
    Route::get('/api/books_min_author/{min}', [BookController::class, 'bookWithMinAuthors']);
    // A * betűvel kezdődő szerzőket add meg!
    Route::get('/api/search_author/{letter}', [BookController::class, 'bookSearchAuthors']);
    //A bejelentkezett felhasználó 3 napnál régebbi előjegyzéseit add meg!
    Route::get('/api/user_old_reservations/{day}', [ReservationController::class, 'userOldReservations']);
    //Bejelentkezett felhasználó azon kölcsönzéseit add meg (copy_id és db), ahol egy példányt legalább db-szor kölcsönzött ki! (együtt)
    Route::get('/api/user_lended_num/{num}', [LendingController::class, 'userLendedNum']);
    // Hosszabbítsd meg a könyvet, ha nincs rá előjegyzés!
    Route::patch('/api/legthen_lended/{copy_id}/{start}', [LendingController::class, 'legthenLended']);
    // jelenleg nálam levő könyvek
    Route::get('/api/currently_reserved_books', [ReservationController::class, 'currentlyReservedBooks']);

    //mail
    Route::get('/send-mail/{email}/{num}', [MailController::class, 'index']);
    //file
    Route::get('file_upload', [FileController::class, 'index']);
    Route::post('file_upload', [FileController::class, 'store'])->name('file.store');


});
Route::delete('/api/delete_non_lended_users', [UserController::class, 'deleteNolendUsers']);
//csak a tesztelés miatt van "kint"
Route::patch('/api/users/password/{id}', [UserController::class, 'updatePassword']);
Route::apiResource('/api/copies', CopyController::class);

Route::get('/api/llsql/{id}', [BookController::class, 'llSql']);

require __DIR__.'/auth.php';
