<?php

namespace App\Http\Controllers;

use App\Models\Copy;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;

class CopyController extends Controller
{
    public function index()
    {
        $copys = response()->json(Copy::all());
        return $copys;
    }
    public function show($id)
    {
        $copy = response()->json(Copy::find($id));
        return $copy;
    }
    public function destroy($id)
    {
        Copy::find($id)->delete();
    }
    public function store(Request $request)
    {
        $task = new Copy();
        $task->user_id = 1;
        $task->book_id = $request->book_id;
        $task->status = 0;
        $task->save();
    }
    public function update(Request $request, $copy_id)
    {
        $copy = Copy::find($copy_id);
        // if($copy->user_id != 1)
        $copy->user_id = $request->user_id;
        $copy->book_id = $request->book_id;
        $copy->status = $request->status;
        $copy->save();
    }
    public function newView()
    {
        $users = User::all();
        $books = Book::all();
        return view("copy.new", ["users"=>$users, "books"=>$books]);
    }
    public function editView($id)
    {
        $users = User::all();
        $books = Book::all();
        $copy = Copy::find($id);
        return view("copy.edit", ["users"=>$users, "books"=>$books, "copy"=>$copy]);
    }
    public function listView()
    {
        $copies = Copy::all();
        $books = Book::all();
        return view("copy.list", ["copies"=>$copies, "books"=>$books]);
    }
}
