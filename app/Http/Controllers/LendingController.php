<?php

namespace App\Http\Controllers;

use App\Models\Copy;
use App\Models\Lending;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LendingController extends Controller
{
    public function index(){
        $lendings =  Lending::all();
        return $lendings;
    }

    public function show ($user_id, $copy_id, $start)
    {
        $lending = Lending::where('user_id', $user_id)->where('copy_id', $copy_id)->where('start', $start)->get();
        return $lending[0];
    }

    public function destroy($user_id, $copy_id, $start)
    {
        LendingController::show($user_id, $copy_id, $start)->delete();
    }

    public function store(Request $request)
    {
        $lending = new Lending();
        $lending->user_id = $request->user_id;
        $lending->copy_id = $request->copy_id;
        $lending->start = $request->start;
        $lending->end = $request->end;
        $lending->extension = $request->extension;
        $lending->notice = $request->notice;
        $lending->save();
    }

    public function update(Request $request, $user_id, $copy_id, $start)
    {
        $lending = LendingController::show($user_id, $copy_id, $start);
        $lending->user_id = $request->user_id;
        $lending->copy_id = $request->copy_id;
        $lending->start = $request->start;
        $lending->end = $request->end;
        $lending->extension = $request->extension;
        $lending->notice = $request->notice;
        $lending->save();
    }

    //view-k:
    public function newView()
    {
        //új rekord(ok) rögzítése
        $users = User::all();
        $copies = Copy::all();
        return view('lending.new', ['users' => $users, 'copies' => $copies]);
    }

    //selects
    public function userLendingsList()
    {
        $user = Auth::user();	//bejelentkezett felhasználó
        $lendings = Lending::with('user_c')
        ->where('user_id','=', $user->id)
        ->get();
        return $lendings;
    }

    public function userLendingsListExtra()
    {
        $user = Auth::user();
        $lendings = Lending::with('user_c')
        ->with('copy_c')
        ->where('user_id','=', $user->id)
        ->get();
        return $lendings;
    }

    public function userLendingsCount()
    {
        $user = Auth::user();
        $lendings = Lending::with('user_c')
        ->where('user_id','=', $user->id)
        ->distinct('copy_id')
        ->count();
        return $lendings;
    }

    public function userLendingsCountExtra()
    {
        $user = Auth::user();
        $lendings = Lending::with('user_c')
        ->where('user_id','=', $user->id)
        ->count();
        return $lendings;
    }

    public function userLendedNum($num)
    {
        $user = Auth::user();
        $lendings = DB::table('lendings as l')
        ->selectRaw('l.copy_id, count(1)')
        ->where('l.user_id', '=', $user->id)
        ->groupBy('l.copy_id')
        ->having('count(1)', '>=', $num)
        ->get();
        return $lendings;
    }

    public function legthenLended($copy_id, $start)
    {
        $user = Auth::user();
        $book = DB::table('lendings as l')
        ->select('c.book_id')
        ->join('copies c', 'l.copy_id', '=', 'c.copy_id')
        ->where('l.user_id', '=', $user->id)
        ->where('l.copy_id', '=', $copy_id)
        ->where('l.start', '=', $start)
        ->get();
        return $book;
    }

    public function bringBack($copy_id, $start)
    {
        $user = Auth::user();
        $lending = LendingController::show($user->id, $copy_id, $start);
        $lending->end = date(now());
        $lending->save();
        DB::table('copies')
        ->where('copy_id', $copy_id)
        ->update(['status' => 0]);
        // DB::store('CALL toStore(?)', array($copy_id));
    }
}
