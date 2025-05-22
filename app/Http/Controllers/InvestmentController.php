<?php

namespace App\Http\Controllers;

use App\Models\TradingMaster;
use App\Models\TradingSubscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class InvestmentController extends Controller
{
    public function index()
    {
        $joined_master_count = TradingSubscription::whereHas('trading_account', function ($q) {
            $q->where('user_id', Auth::id());
        })
            ->distinct('master_meta_login')
            ->count('master_meta_login');

        return Inertia::render('Investment/Listing/InvestmentTable', [
            'subscribedStrategyCount' => $joined_master_count,
        ]);
    }


    public function subscribedStrategy()
    {
        $subscribed_strategy = TradingMaster::with([
            'trading_subscription' => function ($q) {
                $q->with(['trading_account', 'user'])->whereHas('trading_account', function ($qa) {
                    $qa->where('user_id', Auth::id());
                });
            }
        ])
            ->whereHas('trading_subscription.trading_account', function ($q) {
                $q->where('user_id', Auth::id());
            })
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

            $query = TradingMaster::whereHas('trading_subscription', function ($q) {
                $q->whereHas('trading_account', function ($q2) {
                    $q2->where('user_id', Auth::id());
                });
            });

            if ($meta_login) {
                $query->where('trading_masters.meta_login', $meta_login);
            }

            // Now eager load related models
            $query->with([
                'trading_subscription' => function ($q) {
                    $q->whereHas('trading_account', function ($q2) {
                        $q2->where('user_id', Auth::id());
                    });
                },
                'trading_subscription.trading_account',
                'trading_subscription.user'
            ]);

            // seaching
            if ($data['filters']['global']['value']) {
                $keyword = $data['filters']['global']['value'];

                // Filter masters having at least one matching subscription
                $query->whereHas('trading_subscription', function ($q) use ($keyword) {
                    $q->where(function ($subQuery) use ($keyword) {
                        $subQuery->where('subscription_number', 'like', '%' . $keyword . '%')
                            ->orWhere('meta_login', 'like', '%' . $keyword . '%');
                    });
                });

                $query->with(['trading_subscription' => function ($q) use ($keyword) {
                    $q->where(function ($subQuery) use ($keyword) {
                        $subQuery->where('subscription_number', 'like', '%' . $keyword . '%')
                            ->orWhere('meta_login', 'like', '%' . $keyword . '%');
                    });
                }]);
            } else {
                // no search keyword, load all subscriptions normally
                $query->with('trading_subscription');
            }

            // date
            if (!empty($data['filters']['start_join_date']['value']) && !empty($data['filters']['end_join_date']['value'])) {
                $start_join_date = Carbon::parse($data['filters']['start_join_date']['value'])->addDay()->startOfDay();
                $end_join_date = Carbon::parse($data['filters']['end_join_date']['value'])->addDay()->endOfDay();

                // Filter parents by subscription approval_at range (only masters with matching subscriptions)
                $query->whereHas('trading_subscription', function ($q) use ($start_join_date, $end_join_date) {
                    $q->whereBetween('approval_at', [$start_join_date, $end_join_date]);
                });

                // Eager load only subscriptions in date range
                $query->with(['trading_subscription' => function ($q) use ($start_join_date, $end_join_date) {
                    $q->whereBetween('approval_at', [$start_join_date, $end_join_date]);
                }]);
            }

            // status filter
            if ($data['filters']['status']['value']) {
                $query->whereHas('trading_subscription', function ($q) use ($data) {
                    $q->where('status', $data['filters']['status']['value']);
                });

                $query->with('trading_subscription', function ($q) use ($data) {
                    $q->where('status', $data['filters']['status']['value']);
                });
            }

            // // sorting
            if ($data['sortField'] && $data['sortOrder']) {
                $order = $data['sortOrder'] == 1 ? 'asc' : 'desc';

                $query->whereHas('trading_subscription', function ($q) use ($data, $order) {
                    $q->orderBy($data['sortField'], $order);
                });
                
                $query->with('trading_subscription', function ($q) use ($data, $order) {
                    $q->orderBy($data['sortField'], $order);
                });
            } else {
                $query->latest();
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
