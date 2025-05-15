<?php

namespace App\Http\Controllers;

use App\Http\Requests\TopUpRequest;
use App\Models\TopUpProfile;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Services\RunningNumberService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Inertia\Inertia;

class WalletController extends Controller
{
    public function topUp(TopUpRequest $request)
    {
        $user = Auth::user();

        $wallet = Wallet::find($request->wallet_id);

        $topUpProfile = TopUpProfile::find($request->topUpProfile_id);

        $amount = $request->amount;

        $latest_transaction = Transaction::where([
            'user_id' => $user->id,
            'category' => 'wallet',
            'transaction_type' => 'Deposit',
            'status' => 'Processing',
        ])
            ->latest()
            ->first();

        // Check processing transaction
        if (!empty($latest_transaction) && $latest_transaction->amount == $amount) {
            return back()->with('toast', [
                'title' => trans("public.warning"),
                'message' => trans('public.pending_deposit_caption'),
                'type' => 'warning',
            ]);
        } else {
            $transaction = Transaction::create([
                'category' => 'wallet',
                'user_id' => $user->id,
                'to_wallet_id' => $wallet->id,
                'transaction_number' => RunningNumberService::getID('transaction'),
                'transaction_type' => 'deposit',
                'amount' => $amount,
                'transaction_amount' => $amount,
                'old_wallet_amount' => $wallet->balance,
                'new_wallet_amount' => $wallet->balance + $amount,
                'transaction_charges' => 0,
                'conversion_rate' => 0,
                'status' => 'Processing',
            ]);
        }

        $params = [];
        $baseUrl = '';
        switch ($topUpProfile->type) {
            case 'ttpay':
                $vCode = md5($amount . $topUpProfile->payment_app_name . $transaction->transaction_number . $topUpProfile->secondary_key . $topUpProfile->secret_key);
                $params = [
                    'userName' => $user->full_name,
                    'userEmail' => $user->email,
                    'orderNumber' => $transaction->transaction_number,
                    'userId' => $user->id,
                    'amount' => $amount,
                    'merchantId' => $topUpProfile->secondary_key,
                    'vCode' => $vCode,
                    'token' => Str::random(40),
                    'locale' => app()->getLocale(),
                    'topUpProfile_id' => $topUpProfile->id,
                ];
                $baseUrl = $topUpProfile->payment_url . '/payment';
                break;

            default:
                // notification
        }

        // Send response
        $redirectUrl = $baseUrl . "?" . http_build_query($params);
        return Inertia::location($redirectUrl);


        return back()->with('toast', [
            'title' => trans("public.success_request_deposit"),
            'message' => trans("public.successfully_request_deposit"),
            'type' => 'success',
        ]);
    }

    public function deposit_return(Request $request)
    {
        $data = $request->all();

        Log::debug($data);

        $result = [
            'amount' => $data['amount'],
            'userId' => $data['userId'],
            'vCode' => $data['vCode'],
            'orderNumber' => $data['orderNumber'],
            'transactionId' => $data['transactionId'],
            'walletAddress' => $data['walletAddress'] ?? null,
            'status' => $data['status'],
            'sCode' => $data['sCode'] ?? null,
            'transactionHash' => $data['transactionHash'] ?? null,
            'sourceAddress' => $data['sourceAddress'] ?? null,
            'blockTime' => $data['blockTime'] ?? null,
            'paidTime' => $data['paidTime'] ?? null,
            'receivedAmount' => $data['receivedAmount'] ?? null,
            'topUpProfile_id' => $data['topUpProfile_id'] ?? null,
        ];
        $transaction = Transaction::where('user_id', $result['userId'])
            ->where('transaction_number', $result['orderNumber'])
            ->first();

        $selectedTopUpProfile = TopUpProfile::find($result['topUpProfile_id']);

        $sCode1 = md5($result['transactionId'] . $result['orderNumber'] . $result['status'] . $result['amount']);
        $sCode2 = md5($result['walletAddress'] . $sCode1 . $selectedTopUpProfile->payment_app_name . $selectedTopUpProfile->secondary_key);

        if ($result['status'] == 'PENDING') {
            return to_route('dashboard');
        }

        if ($result['sCode'] == $sCode2) {
            $wallet = Wallet::find($transaction->to_wallet_id);

            if ($transaction->status == 'Processing') {
                if ($result['status'] == 'PAID') {
                    $transaction->update([
                        'amount' => $result['receivedAmount'] / 100,
                        'transaction_amount' => $result['receivedAmount'] / 100,
                        'txn_hash' => $result['transactionHash'],
                        'status' => 'Success'
                    ]);

                    $walletTotalBalance = $wallet->balance + $transaction->transaction_amount;
                    $wallet->update([
                        'balance' => $walletTotalBalance,
                    ]);

                    // notification
                } elseif ($result['status'] == 'EXPIRED') {
                    $transaction->update([
                        'status' => 'Failed',
                    ]);
                }
            }
        }

        return to_route('dashboard');
    }

    public function deposit_callback(Request $request)
    {
        $data = $request->all();

        $result = [
            'amount' => $data['amount'],
            'userId' => $data['userId'],
            'vCode' => $data['vCode'],
            'orderNumber' => $data['orderNumber'],
            'transactionId' => $data['transactionId'],
            'walletAddress' => $data['walletAddress'] ?? null,
            'status' => $data['status'],
            'sCode' => $data['sCode'],
            'transactionHash' => $data['transactionHash'] ?? null,
            'sourceAddress' => $data['sourceAddress'] ?? null,
            'blockTime' => $data['blockTime'] ?? null,
            'paidTime' => $data['paidTime'] ?? null,
            'receivedAmount' => $data['receivedAmount'],
            'topUpProfile_id' => $data['topUpProfile_id'] ?? null,
        ];

        $transaction = Transaction::where('user_id', $result['userId'])
            ->where('transaction_number', $result['orderNumber'])
            ->first();

        $selectedTopUpProfile = TopUpProfile::find($result['topUpProfile_id']);

        $sCode1 = md5($result['transactionId'] . $result['orderNumber'] . $result['status'] . $result['amount']);
        $sCode2 = md5($result['walletAddress'] . $sCode1 . $selectedTopUpProfile->payment_app_name . $selectedTopUpProfile->secondary_key);
        $transaction = Transaction::where('user_id', $result['userId'])->where('transaction_number', $result['orderNumber'])->first();

        if ($result['status'] == 'EXPIRED') {
            $transaction->update([
                'status' => 'Rejected',
                'approval_at' => now()
            ]);
        }

        if ($result['sCode'] == $sCode2) {
            $wallet = Wallet::find($transaction->to_wallet_id);

            if ($transaction->status == 'Processing') {
                if ($result['status'] == 'PAID') {
                    $transaction->update([
                        'amount' => $result['receivedAmount'] / 100,
                        'transaction_amount' => $result['receivedAmount'] / 100,
                        'txn_hash' => $result['transactionHash'],
                        'status' => 'Success',
                        'approval_at' => now(),
                        'new_wallet_amount' => $wallet->balance + $result['receivedAmount'] / 100
                    ]);

                    $walletTotalBalance = $wallet->balance + $transaction->transaction_amount;
                    $wallet->update([
                        'balance' => $walletTotalBalance,
                    ]);

                    // notification
                } else {
                    $transaction->update([
                        'txn_hash' => $result['transactionHash'],
                        'status' => 'Rejected'
                    ]);
                }
            }
        }
    }
}
