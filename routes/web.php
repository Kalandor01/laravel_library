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
    Route::delete('/api/delete_non_lended_users', [UserController::class, 'deleteNolendUsers']);
});

// LIBRARIAN
Route::middleware( ['librarian'])->group(function () {
    Route::get('/api/reservations', [ReservationController::class, 'index']);
    Route::get('/api/lendings', [LendingController::class, 'index']);
    Route::get('/api/lendings/{user_id}/{copy_id}/{start}', [LendingController::class, 'show']);
    Route::get('/api/reservations/{copy}/{book}/{start}', [ReservationController::class, 'show']);
    //// selects
    // Rakt??rban l??v?? p??ld??nyok sz??ma.
    Route::get('/api/in_storage_count', [BookController::class, 'inStorageCount']);
    // Bizonyos ??vben kiadott, bizonyos k??nyv rakt??rban l??v?? darabjainak a sz??ma.
    Route::get('/api/book_year_in_storage_count/{id}/{year}', [BookController::class, 'bookYearInStorageCount']);
    // Adott k??nyvh??z (book_id) tartoz?? p??ld??nyok k??lcs??nz??si adatai
    Route::get('/api/book_lendings/{id}', [BookController::class, 'bookLendings']);
});

//SIMPLE USER
Route::middleware(['auth.basic'])->group(function () {
    Route::get('/copy/list', [CopyController::class, 'listView']);
    Route::get('/api/books', [BookController::class, 'index']);
    Route::get('/api/books/{id}', [BookController::class, 'show']);
    ////selects
    // Az eddig kik??lcs??nz??tt k??nyvek list??ja a bejelentkezett felhaszn??l?? ??ltal 
    Route::get('/api/user_lendings', [LendingController::class, 'userLendingsList']);
    // Az eddig kik??lcs??nz??tt k??nyvek list??ja a bejelentkezett felhaszn??l?? ??ltal
    Route::get('/api/user_lendings_extra', [LendingController::class, 'userLendingsListExtra']);
    // H??nyszor k??lcs??nz??tt ki k??nyvet eddig a bejelentkezett felhaszn??l??
    Route::get('/api/user_lendings_count', [LendingController::class, 'userLendingsCount']);
    // H??nyszor k??lcs??nz??tt ki k??nyvet eddig a bejelentkezett felhaszn??l?? (vizsg??ljuk, ha ugyanazt a k??nyvet t??bbsz??r is kik??lcs??n??zte)?
    Route::get('/api/user_lendings_count_extra', [LendingController::class, 'userLendingsCountExtra']);
    // H??ny darab el??jegyz??se van a bejelentkezett felhaszn??l??nak?
    Route::get('/api/user_reservations_count', [ReservationController::class, 'userReservationsCount']);
    // Csoportos??tsd szerz??nk??nt a k??nyveket (nem p??ld??nyokat) a szerz??k ABC szerinti n??vekv?? sorrendj??ben!
    Route::get('/api/books_by_author', [BookController::class, 'booksByAuthor']);

    // Egy adott c??m?? k??nyv p??ld??nyait list??zd ki!
    Route::get('/api/book_copies/{title}', [BookController::class, 'bookCopies']);
    // H??ny darab p??ld??ny van egy adott c??m?? k??nyvb??l?
    Route::get('/api/book_copy_count/{title}', [CopyController::class, 'bookCopyCount']);
    // Add meg a kem??nyk??t??s?? p??ld??nyokat szerz??vel ??s c??mmel! +
    Route::get('/api/hard_books/{isHard}', [BookController::class, 'hardBooks']);
    // Bizonyos ??vben kiadott p??ld??nyok n??vvel ??s c??mmel ki??rat??sa.
    Route::get('/api/books_published_in/{year}', [BookController::class, 'booksPublishedIn']);
    //Hat??rozd meg a k??nyvt??r nyilv??ntart??s??ban legal??bb x k??nyvvel rendelkez?? szerz??ket!
    Route::get('/api/books_min_author/{min}', [BookController::class, 'bookWithMinAuthors']);
    // A * bet??vel kezd??d?? szerz??ket add meg!
    Route::get('/api/search_author/{letter}', [BookController::class, 'bookSearchAuthors']);
    //A bejelentkezett felhaszn??l?? 3 napn??l r??gebbi el??jegyz??seit add meg!
    Route::get('/api/user_old_reservations/{day}', [ReservationController::class, 'userOldReservations']);
    //Bejelentkezett felhaszn??l?? azon k??lcs??nz??seit add meg (copy_id ??s db), ahol egy p??ld??nyt legal??bb db-szor k??lcs??nz??tt ki! (egy??tt)
    Route::get('/api/user_lended_num/{num}', [LendingController::class, 'userLendedNum']);
    // Hosszabb??tsd meg a k??nyvet, ha nincs r?? el??jegyz??s!
    Route::patch('/api/legthen_lended/{copy_id}/{start}', [LendingController::class, 'legthenLended']);
    // jelenleg n??lam lev?? k??nyvek
    Route::get('/api/currently_reserved_books', [ReservationController::class, 'currentlyReservedBooks']);
    // bring back book
    Route::patch('/api/bring_back/{copy_id}/{start}', [LendingController::class, 'bringBack']);

    //mail
    Route::get('/send-mail/{email}/{num}', [MailController::class, 'index']);
    //file
    Route::get('file_upload', [FileController::class, 'index']);
    Route::post('file_upload', [FileController::class, 'store'])->name('file.store');


});
//csak a tesztel??s miatt van "kint"
Route::patch('/api/users/password/{id}', [UserController::class, 'updatePassword']);
Route::apiResource('/api/copies', CopyController::class);

Route::get('/api/llsql/{id}', [BookController::class, 'llSql']);

require __DIR__.'/auth.php';
