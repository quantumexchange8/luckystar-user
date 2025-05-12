<?php

namespace App\Http\Controllers;

use App\Enums\MetaService;
use App\Models\AccountType;
use App\Models\TradingAccount;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Services\RunningNumberService;
use App\Services\TradingAccountService;
use Auth;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Throwable;

class AccountController extends Controller
{
    public function index()
    {
        $account_types = AccountType::where('status', 'active')
            ->get()
            ->toArray();

        return Inertia::render('Accounts/Listing/AccountListing', [
            'accountTypes' => $account_types,
            'accountCount' => TradingAccount::where('status', 'active')->count(),
        ]);
    }

    /**
     * @throws Throwable
     * @throws ConnectionException
     */
    public function createAccount(Request $request)
    {
        Validator::make($request->all(), [
            'account_type_id' => ['required'],
            'leverage' => ['required'],
            'amount' => ['nullable'],
            'wallet_id' => ['required', 'sometimes'],
        ])->setAttributeNames([
            'account_type_id' => trans('public.account_type'),
            'leverage' => trans('public.leverage'),
            'amount' => trans('public.deposit_amount'),
            'wallet_id' => trans('public.wallet'),
        ])->validate();

        $wallet = null;
        $account_type = AccountType::find($request->account_type_id);
        $user = User::withCount('active_trading_accounts')->find(Auth::id());

        if ($request->has('wallet_id')) {
            $wallet = Wallet::find($request->wallet_id);

            if ($request->amount < $account_type->minimum_deposit) {
                throw ValidationException::withMessages([
                    'amount' => trans('public.insufficient_deposit', ['amount' => number_format($account_type->minimum_deposit, 2)]),
                ]);
            }

            if ($wallet->balance < $request->amount) {
                throw ValidationException::withMessages([
                    'amount' => trans('public.insufficient_balance'),
                ]);
            }
        }

        if ($user->active_trading_accounts_count >= $account_type->maximum_account_number) {
            return back()->with('toast', [
                'title' => trans("public.limited_account"),
                'message' => trans("public.toast_limit_account_warning"),
                'type' => 'warning',
            ]);
        }

        $service = new TradingAccountService();
        $leverage = $request->leverage['setting_leverage']['value'];

        if ($service->getConnectionStatus() != 0) {
            return back()->with('toast', [
                'title' => trans("public.connection_error"),
                'message' => trans("public.toast_connection_error"),
                'type' => 'error',
            ]);
        }

        $metaAccount = $service->createUser($user, $account_type, $leverage);
        $amount = $request->amount;
        $trading_account = TradingAccount::firstWhere('meta_login', $metaAccount['login']);

        if ($amount >= $account_type->minimum_deposit && $request->has('wallet_id')) {
            $oldBalance = $wallet->balance;
            $wallet->decrement('balance', $amount);
            $newBalance = $wallet->fresh()->balance;

            $deal = $service->createDeal($trading_account, $amount, 'Deposit', MetaService::DEPOSIT, $account_type);

            Transaction::create([
                'user_id' => $user->id,
                'category' => 'trading_account',
                'transaction_type' => 'deposit',
                'from_wallet_id' => $wallet->id,
                'to_meta_login' => $trading_account->meta_login,
                'ticket' => $deal['deal_Id'] ?? null,
                'transaction_number' => RunningNumberService::getID('transaction'),
                'amount' => $amount,
                'from_currency' => 'USD',
                'to_currency' => 'USD',
                'conversion_rate' => 1,
                'conversion_amount' => $amount,
                'transaction_amount' => $amount,
                'fund_type' => $user->role == 'user' ? MetaService::REAL_FUND : MetaService::DEMO_FUND,
                'old_wallet_amount' => $oldBalance,
                'new_wallet_amount' => $newBalance,
                'status' => 'success',
                'comment' => $deal['conduct_Deal']['comment'] ?? 'Deposit',
                'approval_at' => now(),
            ]);
        }

        return back()->with('toast', [
            'title' => trans("public.success"),
            'message' => trans('public.toast_create_account_success'),
            'type' => 'success',
        ]);
    }

    public function get_trading_account_data(Request $request)
    {
        if ($request->has('lazyEvent')) {
            $data = json_decode($request->only(['lazyEvent'])['lazyEvent'], true);

            $query = TradingAccount::query()
                ->with([
                    'user',
                    'account_type',
                    'trading_user'
                ]);

            $tradingAccounts = $query->paginate($data['rows']);

            return response()->json([
                'success' => true,
                'data' => $tradingAccounts,
            ]);
        }

        return response()->json(['success' => false, 'data' => []]);
    }

    public function accountDeposit(){

    }
}
