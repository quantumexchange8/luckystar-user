<?php

namespace App\Http\Controllers;

use App\Enums\MetaService;
use App\Models\AccountType;
use App\Models\GroupHasUser;
use App\Models\TradingAccount;
use App\Models\TradingMaster;
use App\Models\TradingSubscription;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Services\RunningNumberService;
use App\Services\TradingAccountService;
use Carbon\Carbon;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Throwable;

class StrategyController extends Controller
{
    public function index()
    {
        $group_id = GroupHasUser::firstWhere('user_id', Auth::id())->group_id;

        $strategiesCount = TradingMaster::whereHas('groups', function ($query) use ($group_id) {
            $query->where('group_id', $group_id);
        })->count();

        return Inertia::render('Strategy/Listing/StrategyListing', [
           'strategiesCount' => $strategiesCount
        ]);
    }

    public function getStrategyData(Request $request)
    {
        if ($request->has('lazyEvent')) {
            $data = json_decode($request->only(['lazyEvent'])['lazyEvent'], true);
            $group_id = GroupHasUser::firstWhere('user_id', Auth::id())->group_id;

            $query = TradingMaster::with([
                'account_type',
                'groups',
                'management_fees'
            ])
                ->withCount('active_subscriptions')
                ->withSum('active_subscriptions', 'subscription_amount')
                ->whereHas('groups', function ($query) use ($group_id) {
                    $query->where('group_id', $group_id);
                });

            if ($data['filters']['global']['value']) {
                $keyword = $data['filters']['global']['value'];

                $query->where(function ($query) use ($keyword) {
                    $query->where('name', 'LIKE', '%' . $keyword . '%')
                        ->orWhereHas('group_leader', function ($query) use ($keyword) {
                            $query->where('first_name', 'LIKE', '%' . $keyword . '%')
                                ->orWhere('last_name', 'LIKE', '%' . $keyword . '%')
                                ->orWhere('email', 'LIKE', '%' . $keyword . '%');
                        });
                });
            }

            //sort field/order
            if ($data['sortField'] && $data['sortOrder']) {
                $order = $data['sortOrder'] == 1 ? 'asc' : 'desc';
                $query->orderBy($data['sortField'], $order);
            } else {
                $query->orderByDesc('created_at');
            }

            $groups = $query->paginate($data['rows']);

            foreach ($groups as $group) {
                $group->group_names = $group->groups->pluck('name')->join(', ');
            }

            return response()->json([
                'success' => true,
                'data' => $groups,
                'totalWalletTopUp' => (float) $groups->sum('wallet_top_up'),
                'totalWalletWithdrawal' => (float) $groups->sum('wallet_withdrawal'),
                'totalActiveCapital' => (float) $groups->sum('active_capital'),
            ]);
        }

        return response()->json(['success' => false, 'data' => []]);
    }

    public function strategyDetail()
    {
        return Inertia::render('Strategy/Listing/Detail/StrategyDetail');
    }

    /**
     * @throws Throwable
     * @throws ConnectionException
     */
    public function investStrategy(Request $request)
    {
        Validator::make($request->all(), [
            'trading_master_id' => ['required'],
            'amount' => ['required', 'numeric'],
            'account_id' => ['required'],
        ])->setAttributeNames([
            'trading_master_id' => trans('public.strategy'),
            'amount' => trans('public.amount'),
            'account_id' => trans('public.managed_account'),
        ])->validate();

        $trading_master = TradingMaster::find($request->trading_master_id);
        $trading_account = TradingAccount::find($request->account_id);
        $amount = $request->amount;
        $user = Auth::user();

        if ($amount < $trading_master->minimum_investment) {
            throw ValidationException::withMessages([
                'amount' => trans('public.investment_amount_requirement', [
                    'amount' => $trading_master->minimum_investment
                ]),
            ]);
        }

        if ($trading_account->balance < $amount) {
            throw ValidationException::withMessages([
                'amount' => trans('public.account_insufficient_balance'),
            ]);
        }

        if ($trading_account->balance != $amount) {
            // Withdraw extra balance to cash wallet
            $service = new TradingAccountService();
            $account_type = AccountType::find($trading_account->account_type_id);
            $wallet = Wallet::firstWhere([
                'user_id' => $user->id,
                'type' => 'cash_wallet',
            ]);
            $exceed_balance = $trading_account->balance - $amount;

            $deal = $service->createDeal($trading_account, $exceed_balance, 'Withdraw exceed', MetaService::WITHDRAW, $account_type);

            $oldBalance = $wallet->balance;
            $wallet->increment('balance', $exceed_balance);
            $newBalance = $wallet->fresh()->balance;

            Transaction::create([
                'user_id' => $user->id,
                'category' => 'trading_account',
                'transaction_type' => 'withdraw',
                'to_wallet_id' => $wallet->id,
                'from_meta_login' => $trading_account->meta_login,
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
                'comment' => $deal['conduct_Deal']['comment'] ?? 'Withdrawal Exceed',
                'approval_at' => now(),
            ]);
        }

        TradingSubscription::create([
            'user_id' => $user->id,
            'meta_login' => $trading_account->meta_login,
            'master_meta_login' => $trading_master->meta_login,
            'type' => $trading_master->category,
            'subscription_amount' => $amount,
            'real_fund' => $user->role == 'user' ? $amount : 0,
            'demo_fund' => $user->role == 'user' ? 0 : $amount,
            'subscription_number' => RunningNumberService::getID('subscription'),
            'subscription_period' => $trading_master->investment_period,
            'subscription_period_type' => $trading_master->investment_period_type,
            'settlement_period' => $trading_master->settlement_period,
            'settlement_period_type' => $trading_master->settlement_period_type,
            'status' => 'pending',
        ]);

        return back()->with('toast', [
            'title' => trans('public.success'),
            'message' => trans('public.toast_invest_strategy_success'),
            'type' => 'success',
        ]);
    }
}
