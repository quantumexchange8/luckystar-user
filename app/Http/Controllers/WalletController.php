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
            'transaction_type' => 'top_up',
            'status' => 'processing',
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
                'transaction_type' => 'top_up',
                'to_payment_account_name' => $topUpProfile->payment_name,
                'to_payment_platform' => $topUpProfile->type,
                'to_payment_platform_name' => $topUpProfile->payment_app_name,
                'to_payment_account_no' => $topUpProfile->account_number,
                'to_bank_branch_address' => $topUpProfile->to_bank_branch_address,
                'amount' => $amount,
                'old_wallet_amount' => $wallet->balance,
                'transaction_charges' => 0,
                'conversion_rate' => 0,
                'status' => 'processing',
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
                ];
                $baseUrl = $topUpProfile->payment_url . '/payment';
                break;

            default:
                // notification
        }

        // Send response
        $redirectUrl = $baseUrl . "?" . http_build_query($params);
        return Inertia::location($redirectUrl);
    }

    public function deposit_return(Request $request)
    {
        return to_route('dashboard');
    }

    public function deposit_callback(Request $request)
    {
        $data = $request->all();

        Log::debug($data);
        $result = [
            "token" => $data['vCode'],
            "from_wallet_address" => $data['from_wallet'],
            "to_wallet_address" => $data['to_wallet'],
            "txn_hash" => $data['txID'],
            "transaction_number" => $data['transaction_number'],
            "amount" => $data['transfer_amount'],
            "transfer_amount_type" => $data['transfer_amount_type'],
            "status" => $data["status"],
            "remarks" => 'System Approval',
        ];

        $transaction = Transaction::query()
            ->where([
                'transaction_number' => $result['transaction_number'],
                'status' => 'processing'
            ])
            ->latest()
            ->first();

        Log::debug($transaction);

        $selected_profile = TopUpProfile::firstWhere($transaction->to_payment_platform_name);

        if ($transaction) {
            $dataToHash = md5($transaction->transaction_number . $selected_profile->payment_app_name . $selected_profile->secondary_key);
            $status = $result['status'] == 'success' ? 'success' : 'failed';

            if ($result['token'] === $dataToHash) {
                $transaction->update([
                    'from_token_address' => $result['from_wallet_address'],
                    'to_token_address' => $result['to_wallet_address'],
                    'txn_hash' => $result['txn_hash'],
                    'transaction_charges' => 0,
                    'status' => $status,
                    'remarks' => $result['remarks'],
                    'approval_at' => now()
                ]);

                if ($result['transfer_amount_type'] == 'invalid') {
                    $transaction->update([
                        'transaction_amount' => $result['amount'],
                        'status' => 'processing',
                    ]);
                } else {
                    $transaction->update([
                        'amount' => $result['amount'],
                        'transaction_amount' => $result['amount'],
                        'status' => $status,
                        'remarks' => $result['remarks'],
                        'approval_at' => now()
                    ]);
                }

                if ($transaction->status == 'success') {
                    if ($transaction->transaction_type == 'top_up') {
                        $wallet = Wallet::find($transaction->to_wallet_id);
                        $wallet->balance += $result['amount'];
                        $wallet->save();

                        $transaction->update([
                            'new_wallet_amount' => $wallet->balance
                        ]);

//                        Notification::route('mail', $transaction->user->email)->notify(new DepositConfirmationNotification($transaction));
                    }
                }
            }
        }

        return response()->json(['success' => false, 'message' => 'Deposit Failed']);
    }
}
