<?php

namespace App\Http\Controllers;

use Illuminate\{Http\Request, Support\Facades\Auth};
use App\Affiliation;

class AffiliationController extends Controller
{
    public function add(Request $request){
        $affiliation = new Affiliation;
        $affiliation->user_id = Auth::id();
        $affiliation->device_id = $request->device_id;
        $affiliation->zone_id = $request->zone_id;
        $affiliation->affiliate_user_id = $request->affiliate_user_id;
        $affiliation->start_affiliation = $request->time_from;
        $affiliation->end_affiliation = $request->time_to;
        $affiliation->save();
        return $affiliation->id;
    }

    public function getAffiliationsToConfirm(){
        $for_user = Auth::id();
        $affiliationsToConfirm = Affiliation::where('affiliate_user_id', $for_user)->where('status', 0)->get();
        return $affiliationsToConfirm;
    }

    public function confirmAlliliation($id){
        $affiliation = Affiliation::find($id);
        $affiliation->status = 1;
        $affiliation->save();
    }

    public function rejectAffiliation($id){
        $affiliation = Affiliation::find($id);
        $affiliation->status = 3;
        $affiliation->save();
    }

    function bindZoneToAffilation(Request $request){
        $affiliation = Affiliation::find($request->affiliation_id);
        $affiliation->zone_id = $request->zone_id;
        $affiliation->save();
    }

}
