<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        $books = response()->json(Book::all());
        return $books;
    }
    public function show($id)
    {
        $book = response()->json(Book::find($id));
        return $book;
    }
    public function destroy($id)
    {
        Book::find($id)->delete();
    }
    public function store(Request $request)
    {
        $book = new Book();
        $book->author = $request->author;
        $book->title = $request->title;
        $book->save();
    }
    public function update(Request $request, $id)
    {
        $book = Book::find($id);
        $book->author = $request->author;
        $book->title = $request->title;
        $book->save();
    }
    public function newView()
    {
        return view("book.new", []);
    }
    public function editView($id)
    {
        $book = Book::find($id);
        return view("book.edit", ["book"=>$book]);
    }
    public function listView()
    {
        $books = Book::all();
        return view("book.list", ["books"=>$books]);
    }
}
