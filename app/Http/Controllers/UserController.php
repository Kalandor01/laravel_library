<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function showAll()
    {
        $users = response()->json(User::all());
        return $users;
    }
    public function show($id)
    {
        $user = response()->json(User::find($id));
        return $user;
    }
    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect('/user/list');
    }
    public function make(Request $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        return redirect('/user/list');
    }
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        return redirect('/user/list');
    }
    public function newView()
    {
        return view("user.new", []);
    }
    public function editView($id)
    {
        $user = User::find($id);
        return view("user.edit", ["user"=>$user]);
    }
    public function listView()
    {
        $users = User::all();
        return view("user.list", ["users"=>$users]);
    }
}
