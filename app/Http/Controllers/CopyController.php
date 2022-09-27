<?php

namespace App\Http\Controllers;

use App\Models\Copy;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;

class CopyController extends Controller
{
    public function showAll()
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
        return redirect('/copy/list');
    }
    public function make(Request $request)
    {
        $task = new Copy();
        $task->user_id = $request->user_id;
        $task->book_id = $request->book_id;
        $task->status = $request->status;
        $task->save();
        return redirect('/copy/list');
    }
    public function update(Request $request, $id)
    {
        $task = Copy::find($id);
        $task->user_id = $request->user_id;
        $task->book_id = $request->book_id;
        $task->status = $request->status;
        $task->save();
        return redirect('/copy/list');
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
