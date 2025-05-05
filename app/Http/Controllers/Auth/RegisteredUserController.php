<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
use App\Services\RunningNumberService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
        $request->validate([
            'username' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'identity_number' => ['required', 'unique:' . User::class],
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'dial_code' => ['required'],
            'phone' => ['required', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $dial_code = $request->dial_code;

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
        ]);

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

        return redirect(route('dashboard', absolute: false));
    }
}
