<?php

namespace App\Http\Controllers;

use App\PublicKey;
use Illuminate\Http\Request;

class WalletInstructionsController extends Controller
{
    protected function savePublicKey(Request $request) {
        $this->validate($request, [
            'address' => 'required',
            'public_key' => 'required',
        ], [
            'address.required' => 'Missing parameters.',
            'public_key.required' => 'Missing parameters.'
        ]);

        //if($request->input('password') == getenv('CROSS_WEBSITE_PASSWORD')) {
        if (strlen(trim($request->input('address'))) == 42) {
            $check_key = PublicKey::where(array('address' => trim($request->input('address'))))->get()->first();
            if (!$check_key) {
                $key = new PublicKey();
                $key->address = trim($request->input('address'));
                $key->public_key = trim($request->input('public_key'));
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

    protected function saveMobileDeviceId(Request $request) {
        $this->validate($request, [
            'address' => 'required',
            'mobile_device_id' => 'required',
        ], [
            'address.required' => 'Missing parameters.',
            'mobile_device_id.required' => 'Missing parameters.'
        ]);

        //if($request->input('password') == getenv('CROSS_WEBSITE_PASSWORD')) {
        if (strlen(trim($request->input('address'))) == 42) {
            $key = PublicKey::where(array('address' => trim($request->input('address'))))->get()->first();
            if ($key) {
                $key->mobile_device_id = trim($request->input('mobile_device_id'));
                $key->save();
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false]);
            }
        } else {
            return response()->json(['error' => true]);
        }
        //}
        //return response()->json(['error' => true]);
    }

    protected function sendPushNotificationIfLegit(Request $request) {
        $this->validate($request, [
            'address' => 'required',
            'title' => 'required',
            'body' => 'required'
        ], [
            'address.required' => 'Missing parameters.',
            'title.required' => 'Missing parameters.',
            'body.required' => 'Missing parameters.'
        ]);

        if (strlen(trim($request->input('address'))) == 42) {
            $key = PublicKey::where(array('address' => trim($request->input('address'))))->get()->first();
            if ($key && !empty($key->mobile_device_id)) {
                $this->sendPushNotification(array($key->mobile_device_id), trim($request->input('title')), trim($request->input('body')));
            } else {
                return response()->json(['success' => false]);
            }
        } else {
            return response()->json(['error' => true]);
        }
    }
}
