<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    public function index(){
        $books =  Book::all();
        return $books;
    }
    
    public function show($id)
    {
        $book = Book::find($id);
        return $book;
    }
    public function destroy($id)
    {
        Book::find($id)->delete();
    }
    public function store(Request $request)
    {
        $Book = new Book();
        $Book->author = $request->author;
        $Book->title = $request->title;
        $Book->save();
    }

    public function update(Request $request, $id)
    {
        $Book = Book::find($id);
        $Book->author = $request->author;
        $Book->title = $request->title;
    }

    public function bookCopies($title)
    {	
        $copies = Book::with('copy_c')
        ->where('title','=', $title)
        ->get();
        return $copies;
    }

    public function hardBooks($isHard){
        $books = DB::table('copies as c')
        ->join('books as b', 'b.book_id', '=', 'c.book_id')
        ->where('c.hardcovered', '=', ($isHard=='1'))
        ->get();
        return $books;
    }

    public function booksPublishedIn($year){
        $books = DB::table('books as b')
        ->join('copies as c', 'c.book_id', '=', 'b.book_id')
        ->whereRaw("b.book_id in (select book_id from copies where publication = ${year})")
        ->get();
        return $books;
    }

    public function inStorageCount(){
        $books = DB::table('copies as c')
        ->where('c.status', '=', 0)
        ->orWhere('c.status', '=', 2)
        ->count();
        return $books;
    }

    public function bookYearInStorageCount($id, $year){
        $books = DB::table('books as b')
        ->join('copies as c', 'c.book_id', '=', 'b.book_id')
        ->where('status', '=', '0')
        ->orWhere('status', '=', '2')
        ->where('b.book_id', '=', $id)
        ->where('publication', '=', $year)
        ->count();
        return $books;
    }

    public function bookLendings($id){
        $books = DB::table('books as b')
        ->join('copies as c', 'c.book_id', '=', 'b.book_id')
        ->join('lendings as l', 'l.copy_id', '=', 'c.copy_id')
        ->where('b.book_id', '=', $id)
        ->get();
        return $books;
    }

    public function llSql($id){
        //támadható: /api/llsql/1 and user_id = 1
        // $books = DB::select(DB::raw("
        //     SELECT *
        //     FROM lendings
        //     WHERE copy_id = ${id}
        // "));
        $books = DB::select(DB::raw("
            SELECT *
            FROM lendings
            WHERE copy_id = :id
        "), array(
            'id' => $id,
        ));
        return $books;
    }
}
