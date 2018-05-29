<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Affiliation;
use App\User;
use Illuminate\Support\Facades\Auth;


class AffiliateUserController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){

        $currentUser = Auth::id();
        $affiliateUsers = Affiliation::where('user_id', $currentUser)->get();
        return view('affiliate_user.index', ['affiliateUsers' => $affiliateUsers]);


    }

    public function destroy($id){
        Affiliation::destroy($id);
        return redirect('/affiliate-user');
    }

    public function search(Request $request){
        $name = $request->name;

        $users = User::where('email','LIKE', '%' . $name . '%')->get();

        return view('affiliate_user.search',['users' => $users]);
    }

}
