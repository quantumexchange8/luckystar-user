<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TopUpRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'wallet_id' => ['required'],
            'topUpProfile_id' => ['required'],
            'amount' => ['required', 'numeric', 'min:1'],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

     public function attributes(): array
    {
        return [
            'wallet_id' => trans('public.wallet'),
            'topUpProfile_id' => trans('public.top_up_profile'),
            'amount' => trans('public.amount'),
        ];
    }
}
