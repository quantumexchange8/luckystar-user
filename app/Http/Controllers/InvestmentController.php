<?php

namespace App\Http\Controllers;

use App\Models\TradingMaster;
use App\Models\TradingSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class InvestmentController extends Controller
{
    public function index()
    {
        $subscribed_strategy_count = DB::table('trading_subscriptions')
            ->where('user_id', Auth::id())
            ->select('meta_login', 'master_meta_login')
            ->distinct()
            ->get()
            ->count();

        return Inertia::render('Investment/Listing/InvestmentTable', [
            'subscribedStrategyCount' => $subscribed_strategy_count,
        ]);
    }

    public function subscribedStrategy()
    {
        $distinctSubscriptionIds = TradingSubscription::where('user_id', Auth::id())
            ->selectRaw('MIN(id) as id') // pick the first ID for each unique pair
            ->groupBy('meta_login', 'master_meta_login')
            ->pluck('id');

        $subscribed_strategy = TradingSubscription::with([
            'trading_master',
            'trading_account',
            'user',
        ])
            ->whereIn('id', $distinctSubscriptionIds)
            ->get();

        return response()->json([
            'subscribedStrategy' => $subscribed_strategy,
        ]);
    }

    public function subscribedStrategyData(Request $request)
    {
        if ($request->has('lazyEvent')) {
            $data = json_decode($request->only(['lazyEvent'])['lazyEvent'], true);
            $meta_login = $data['filters']['meta_login'];
            $master_meta_login = $data['filters']['master_meta_login'];

            $query = TradingSubscription::with([
                'trading_master',
                'trading_account',
                'user',
            ])
                ->where([
                    'user_id' => Auth::id(),
                    'meta_login' => $meta_login,
                    'master_meta_login' => $master_meta_login,
                ]);

            if ($data['filters']['global']['value']) {
                $keyword = $data['filters']['global']['value'];

                $query->where(function ($q) use ($keyword) {
                    $q->where('master_meta_login', 'like', '%' . $keyword . '%')
                        ->orWhere('meta_login', 'like', '%' . $keyword . '%')
                        ->orWhere('subscription_number', 'like', '%' . $keyword . '%');
                });
            }

            // global
            if (!empty($data['filters']['start_join_date']['value']) && !empty($data['filters']['end_join_date']['value'])) {
                $start_join_date = Carbon::parse($data['filters']['start_join_date']['value'])->addDay()->startOfDay();
                $end_join_date = Carbon::parse($data['filters']['end_join_date']['value'])->addDay()->endOfDay();

                $query->whereBetween('approval_at', [$start_join_date, $end_join_date]);
            }

            // date
            if ($data['sortField'] && $data['sortOrder']) {
                $order = $data['sortOrder'] == 1 ? 'asc' : 'desc';
                $query->orderBy($data['sortField'], $order);
            } else {
                $query->orderByDesc('approval_at');
            }

            // status
            if ($data['filters']['status']['value']) {
                $query->where('status', $data['filters']['status']['value']);
            }

            $subscribedStrategy = $query->paginate($data['rows']);

            return response()->json([
                'success' => true,
                'data' => $subscribedStrategy,
            ]);
        }

        return response()->json(['success' => false, 'data' => []]);
    }
}
