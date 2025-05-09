<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\User;
use App\Models\Wallet;
use App\Services\GroupService;
use App\Services\RunningNumberService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        Validator::make($request->all(), [
            'username' => ['required', 'alpha_dash',  'regex:/^[a-zA-Z0-9\p{Han}._\- ]+$/u', 'max:255', 'unique:'.User::class],
            'first_name' => ['required', 'regex:/^[a-zA-Z0-9\p{Han}. ]+$/u', 'max:255'],
            'last_name' => ['required', 'regex:/^[a-zA-Z0-9\p{Han}. ]+$/u', 'max:255'],
            'email' => ['required', 'string', 'email'],
            'dial_code' => ['required'],
            'phone' => ['required', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'referral_code' => ['nullable', 'string', 'max:255', 'exists:users,referral_code'],
        ])->setAttributeNames([
            'username' => trans('public.username'),
            'first_name' => trans('public.first_name'),
            'last_name' => trans('public.last_name'),
            'email' => trans('public.email'),
            'dial_code' => trans('public.dial_code') . '/' .  trans('public.phone'),
            'phone' => trans('public.dial_code') . '/' .  trans('public.phone'),
            'password' => trans('public.password'),
            'referral_code' => trans('public.referral_code'),
        ])->validate();

        $dial_code = $request->dial_code;

        $referrer = null;
        $referral_code = $request->referral_code;

        if ($referral_code) {
            $referrer = User::firstWhere('referral_code', $referral_code);
        }

        if (!$referrer) {
            $referrer = User::find(2);
        }

        $referrer_id = $referrer->id;
        $hierarchyList = empty($referrer['hierarchyList']) ? "-$referrer_id-" : "{$referrer['hierarchyList']}$referrer_id-";

        $user = User::create([
            'username' => $request->username,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'identity_number' => $request->identity_number,
            'email' => $request->email,
            'dial_code' => $dial_code['phone_code'],
            'phone' => $request->phone,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'upline_id' => $referrer_id,
            'hierarchyList' => $hierarchyList,
        ]);

        $user->setReferralId();

        $id_no = 'LID' . Str::padLeft($user->id, 6, "0");
        $user->id_number = $id_no;
        $user->save();

        if ($referrer->group) {
            (new GroupService())->addUserToGroup($referrer->group->group_id, $user->id);
            $group_rank_setting = $referrer->group->group->group_rank_settings()->first();
            $user->setting_rank_id = $group_rank_setting->id;
        } else {
            (new GroupService())->addUserToGroup(Group::first()->id, $user->id);
        }

        Wallet::create([
            'user_id' => $user->id,
            'type' => 'cash_wallet',
            'address' => "LS-CW-". Str::padLeft($user->id, 7, "0"),
            'currency' => 'USD',
            'currency_symbol' => '$'
        ]);

        Wallet::create([
            'user_id' => $user->id,
            'type' => 'bonus_wallet',
            'address' => "LS-BW-". Str::padLeft($user->id, 7, "0"),
            'currency' => 'USD',
            'currency_symbol' => '$'
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false))->with('toast', [
            'title' => trans("public.success"),
            'message' => trans('public.toast_register_success'),
            'type' => 'success',
        ]);
    }
}
