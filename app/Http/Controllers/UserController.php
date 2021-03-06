<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function edit(){
        $id = Auth::id();
        $user = User::find($id);
        return view('user.edit', ['user' => $user]);
    }

    public function update(Request $request){
        $user = User::find(Auth::id());
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->save();
        return redirect('/profile')->with('success', 'Профиль обновлен');
    }

}
