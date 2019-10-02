<?php

namespace App\Http\Controllers;

use App\PublicKey;
use Illuminate\Http\Request;

class WalletInstructionsController extends Controller
{
    protected function savePublicKey(Request $request) {
        //if($request->input('password') == getenv('CROSS_WEBSITE_PASSWORD')) {
        if(strlen($request->input('address')) != 42) {
            $check_key = PublicKey::where(array('address' => $request->input('address')))->get()->first();
            if(!$check_key) {
                $key = new PublicKey();
                $key->address = $request->input('address');
                $key->public_key = $request->input('public_key');
                $key->save();
                return response()->json(['success' => true]);
            }
            return response()->json(['success' => false]);
        } else {
            return response()->json(['error' => true]);
        }
        //}
        //return response()->json(['error' => true]);
    }
}
