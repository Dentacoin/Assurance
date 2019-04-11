<?php

namespace App\Http\Controllers;

use App\PublicKey;
use Illuminate\Http\Request;

class WalletInstructionsController extends Controller
{
    protected function getView()   {
        return view('pages/wallet-instructions');
    }

    protected function getPublicKeys() {
        return PublicKey::all();
    }

    protected function savePublicKey(Request $request) {
        $key = new PublicKey();
        $key->address = $request->input('address');
        $key->public_key = $request->input('public_key');
        $key->save();
    }
}
