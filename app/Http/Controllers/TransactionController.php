<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TransactionController extends Controller
{
    public function index()
    {
        return Inertia::render('Report/Listing/Transaction/TransactionListing');
    }

    public function getCashWalletData(Request $request)
    {
        if ($request->has('lazyEvent')) {
            $data = json_decode($request->only(['lazyEvent'])['lazyEvent'], true);

            $query = Transaction::query()
                ->with([
                    'user:id,first_name,last_name,email,upline_id',
                    'user.upline:id,first_name,last_name,email',
                    'from_wallet:id,type,address,currency_symbol',
                    'to_wallet:id,type,address,currency_symbol',
                ])
                ->where('user_id', Auth::id())
                ->whereNot('status', 'processing')
                ->where('category', 'cash_wallet');

            //global filter
            if (!empty($data['filters']['global']['value'])) {
                $query->whereHas('user', function ($q) use ($data) {
                    $keyword = $data['filters']['global']['value'];

                    // Filter on the 'name' column in the related 'user' table
                    $q->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$keyword}%"])
                        ->orWhere('email', 'like', '%' . $keyword . '%')
                        ->orWhere('transaction_number', 'like', '%' . $keyword . '%');
                });
            }

            //date filter
            if (!empty($data['filters']['start_date']['value']) && !empty($data['filters']['end_date']['value'])) {
                $start_date = Carbon::parse($data['filters']['start_date']['value'])->addDay()->startOfDay(); //add day to ensure capture entire day
                $end_date = Carbon::parse($data['filters']['end_date']['value'])->addDay()->endOfDay();

                $query->whereBetween('approval_at', [$start_date, $end_date]);
            }

            //status filter
            if ($data['filters']['status']['value']) {
                $query->where('status', $data['filters']['status']['value']);
            }

            // type filter
            if ($data['filters']['type']['value']) {
                $query->where('transaction_type', $data['filters']['type']['value']);
            }


            //sort field/order
            if ($data['sortField'] && $data['sortOrder']) {
                $order = $data['sortOrder'] == 1 ? 'asc' : 'desc';
                $query->orderBy($data['sortField'], $order);
            } else {
                $query->latest();
            }

            $cashWallet = $query->paginate($data['rows']);

            $successAmount = (clone $query)
                ->where('status', 'success')
                ->sum('transaction_amount');

            $failAmount = (clone $query)
                ->where('status', 'failed')
                ->sum('transaction_amount');

            $totalAmount = $successAmount + $failAmount;

            $cashWalletCounts = (clone $query)
                ->count();

            return response()->json([
                'success' => true,
                'data' => $cashWallet,
                'cashWalletCounts' => $cashWalletCounts,
                'successAmount' => $successAmount,
                'failAmount' => $failAmount,
                'totalAmount' => $totalAmount,
            ]);
        }

        return response()->json(['success' => false, 'data' => []]);
    }

    public function getBonusWalletData(Request $request)
    {
        if ($request->has('lazyEvent')) {
            $data = json_decode($request->only(['lazyEvent'])['lazyEvent'], true);

            $query = Transaction::query()
                ->with([
                    'user:id,first_name,last_name,email,upline_id',
                    'user.upline:id,first_name,last_name,email',
                    'from_wallet:id,type,address,currency_symbol',
                    'to_wallet:id,type,address,currency_symbol',
                ])
                ->where('user_id', Auth::id())
                ->whereNot('status', 'processing')
                ->where('category', 'bonus_wallet');

            //global filter
            if (!empty($data['filters']['global']['value'])) {
                $query->whereHas('user', function ($q) use ($data) {
                    $keyword = $data['filters']['global']['value'];

                    // Filter on the 'name' column in the related 'user' table
                    $q->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$keyword}%"])
                        ->orWhere('email', 'like', '%' . $keyword . '%')
                        ->orWhere('transaction_number', 'like', '%' . $keyword . '%');
                });
            }

            //date filter
            if (!empty($data['filters']['start_date']['value']) && !empty($data['filters']['end_date']['value'])) {
                $start_date = Carbon::parse($data['filters']['start_date']['value'])->addDay()->startOfDay(); //add day to ensure capture entire day
                $end_date = Carbon::parse($data['filters']['end_date']['value'])->addDay()->endOfDay();

                $query->whereBetween('approval_at', [$start_date, $end_date]);
            }

            //status filter
            if ($data['filters']['status']['value']) {
                $query->where('status', $data['filters']['status']['value']);
            }

            // type filter
            if ($data['filters']['type']['value']) {
                $query->where('transaction_type', $data['filters']['type']['value']);
            }

            //sort field/order
            if ($data['sortField'] && $data['sortOrder']) {
                $order = $data['sortOrder'] == 1 ? 'asc' : 'desc';
                $query->orderBy($data['sortField'], $order);
            } else {
                $query->latest();
            }

            $cashWallet = $query->paginate($data['rows']);

            $successAmount = (clone $query)
                ->where('status', 'success')
                ->sum('transaction_amount');

            $failAmount = (clone $query)
                ->where('status', 'failed')
                ->sum('transaction_amount');

            $totalAmount = $successAmount + $failAmount;

            $bonusWalletCounts = (clone $query)
                ->count();

            return response()->json([
                'success' => true,
                'data' => $cashWallet,
                'bonusWalletCounts' => $bonusWalletCounts,
                'successAmount' => $successAmount,
                'failAmount' => $failAmount,
                'totalAmount' => $totalAmount,
            ]);
        }

        return response()->json(['success' => false, 'data' => []]);
    }
}
