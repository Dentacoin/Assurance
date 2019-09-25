<?php

namespace App\Http\Controllers;

use App\PublicKey;
use Illuminate\Http\Request;

class WalletInstructionsController extends Controller
{
    protected function savePublicKey(Request $request) {
        if($request->input('password') == getenv('CROSS_WEBSITE_PASSWORD')) {
            $check_key = PublicKey::where(array('address' => $request->input('address')))->get()->first();
            if(!$check_key) {
                $key = new PublicKey();
                $key->savePublicKeyaddress = $request->input('address');
                $key->public_key = $request->input('public_key');
                $key->save();
                return response()->json(['success' => true]);
            }
            return response()->json(['success' => false]);
        }
        return response()->json(['error' => true]);
    }
}
