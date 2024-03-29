<?php

namespace App\Http\Controllers;

use App\PublicKey;
use App\WalletRelayedMessage;
use Illuminate\Http\Request;
use Log;

class WalletController extends Controller
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

    protected function saveMessageRelay(Request $request) {
        $this->validate($request, [
            'wallet' => 'required',
            'tx_hash' => 'required',
        ], [
            'wallet.required' => 'Missing parameters.',
            'tx_hash.required' => 'Missing parameters.'
        ]);

        if (strlen(trim($request->input('wallet'))) == 42) {
            $message = new WalletRelayedMessage();
            $message->wallet = trim($request->input('wallet'));
            $message->tx_hash = trim($request->input('tx_hash'));
            $message->save();

            return response()->json(['success' => true]);
        } else {
            return response()->json(['error' => true]);
        }
    }

    protected function getMessageRelaysForWallet($wallet) {
        if (strlen(trim($wallet)) == 42) {
            $messages = WalletRelayedMessage::where(array('wallet' => trim($wallet)))->get()->all();
            if ($messages) {
                return response()->json(['success' => true, 'data' => $messages]);
            } else {
                return response()->json(['error' => true]);
            }
        } else {
            return response()->json(['error' => true]);
        }
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
        if (strlen(trim($request->input('address'))) == 42 && !empty(trim($request->input('mobile_device_id'))) && trim($request->input('mobile_device_id')) != 'null') {
            $key = PublicKey::where(array('address' => trim($request->input('address'))))->get()->first();
            if ($key) {
                // if there is already existing public key with THIS mobile_device_id then delete it
                $keyToEmptyMobileDeviceId = PublicKey::where(array('mobile_device_id' => trim($request->input('mobile_device_id'))))->get()->first();
                if (!empty($keyToEmptyMobileDeviceId)) {
                    $keyToEmptyMobileDeviceId->mobile_device_id = NULL;
                    $keyToEmptyMobileDeviceId->save();
                }

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
            'from' => 'required',
            'to' => 'required',
            'amount' => 'required',
            'type' => 'required',
            'hash' => 'required'
        ], [
            'from.required' => 'Missing parameters.',
            'to.required' => 'Missing parameters.',
            'amount.required' => 'Missing parameters.',
            'type.required' => 'Missing parameters.',
            'hash.required' => 'Missing parameters.'
        ]);

        $hashParams = array(
            'from' => trim($request->input('from')),
            'to' => trim($request->input('to')),
            'amount' => trim($request->input('amount')),
            'type' => trim($request->input('type'))
        );

        Log::useDailyFiles(storage_path().'/logs/Wallet-test-logs.log');
        Log::info('sendPushNotificationIfLegit method.', ['hashParams' => json_encode($hashParams), 'hash' => trim($request->input('hash')), 'hash1' => hash('sha256', getenv('WALLET_REQUESTS_ENCRYPTION_KEY').json_encode($hashParams)), 'hash_condition' => trim($request->input('hash')) == hash('sha256', getenv('WALLET_REQUESTS_ENCRYPTION_KEY').json_encode($hashParams))]);

        if (trim($request->input('hash')) == hash('sha256', getenv('WALLET_REQUESTS_ENCRYPTION_KEY').json_encode($hashParams))) {
            if (strlen(trim($request->input('from'))) == 42 && strlen(trim($request->input('to'))) == 42) {
                $key = PublicKey::where(array('address' => trim($request->input('to'))))->get()->first();
                if ($key && !empty($key->mobile_device_id)) {
                    $formattedAddress = mb_substr(trim($request->input('from')), 0, 5) . '...' . mb_substr(trim($request->input('from')), strlen(trim($request->input('from'))) -5, 5);
                    $type = trim($request->input('type'));
                    if ($type == 'ETH2.0') {
                        $type = 'Optimistic ETH';
                    } else if ($type == 'DCN2.0') {
                        $type = 'DCN on Optimism';
                    }
                    $this->sendPushNotification($key->mobile_device_id, 'Received ' . trim($request->input('amount')). ' ' . $type, 'From: ' . $formattedAddress);
                } else {
                    return response()->json(['success' => false, 'message' => 'False mobile ID.']);
                }
            } else {
                return response()->json(['success' => false, 'message' => 'False address.']);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'False hash.']);
        }
    }
}