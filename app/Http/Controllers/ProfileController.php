<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Kyc;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class ProfileController extends Controller
{
    public function index()
    {
        $user = User::with([
            'country',
            'rank',
            'media',
            'kycs.media',
        ])->find(Auth::id());

        $user->profile_photo = $user->getFirstMediaUrl('profile_photo');

        $kycByCategory = $user->kycs->keyBy('category');

        if ($identityKyc = $kycByCategory->get('proof_of_identity')) {
            $user->proof_type = $identityKyc->proof_type;
            if ($identityKyc->proof_type == 'photo_id') {
                $user->front_identity = $identityKyc->getFirstMediaUrl('front_identity');
                $user->back_identity  = $identityKyc->getFirstMediaUrl('back_identity');
            } else {
                $user->passport_identity  = $identityKyc->getFirstMediaUrl('passport_identity');
            }
            $user->identity_status = $identityKyc->kyc_status;
        } else {
            $user->identity_status = 'unverified';
        }

        $residencyKyc = $kycByCategory->get('proof_of_residency');

        $user->residency_status = $residencyKyc?->kyc_status ?? 'unverified';
        $user->residency_proof  = $residencyKyc?->getFirstMediaUrl('residency_proof') ?? null;

        return Inertia::render('Profile/Profile', [
            'user' => $user,
        ]);
    }

    public function getKycFiles()
    {
        $kyc = Kyc::where([
            'user_id' => Auth::id(),
        ])->first();

        $files = [
            'front_identity'    => $kyc?->getFirstMediaUrl('front_identity'),
            'back_identity'     => $kyc?->getFirstMediaUrl('back_identity'),
            'passport_identity' => $kyc?->getFirstMediaUrl('passport_identity'),
            'residency_proof' => $kyc?->getFirstMediaUrl('residency_proof'),
        ];

        return response()->json($files);
    }

    /**
     * @throws FileIsTooBig
     * @throws FileDoesNotExist
     */
    public function uploadIdentityProof(Request $request)
    {
        Validator::make($request->all(), [
            'document_type' => ['required'],
            'front_identity' => ['nullable', 'image'],
            'back_identity' => ['nullable', 'image'],
            'passport_identity' => ['nullable', 'image'],
        ])->setAttributeNames([
            'document_type' => trans('public.document_type'),
            'front_identity' => trans('public.front_identity'),
            'back_identity' => trans('public.back_identity'),
            'passport_identity' => trans('public.passport'),
        ])->validate();

        $proof_type = $request->document_type;

        if ($proof_type == 'photo_id') {
            if ($request->front_identity == null) {
                throw ValidationException::withMessages([
                    'front_identity' => trans('validation.required', ['attribute' => trans('public.front_identity')]),
                ]);
            }

            if ($request->back_identity == null) {
                throw ValidationException::withMessages([
                    'back_identity' => trans('validation.required', ['attribute' => trans('public.back_identity')]),
                ]);
            }
        } elseif ($proof_type == 'passport' && $request->passport_identity == null) {
            throw ValidationException::withMessages([
                'passport_identity' => trans('validation.required', ['attribute' => trans('public.passport')]),
            ]);
        }

        $exist_kyc = Kyc::where([
            'user_id' => Auth::id(),
            'category' => 'proof_of_identity',
        ])
            ->whereIn('kyc_status', ['verified', 'pending'])
            ->exists();

        if ($exist_kyc) {
            return back()->with('toast', [
                'title' => trans('public.invalid_action'),
                'message' => trans('public.toast_file_submitted_warning'),
                'type' => 'warning',
            ]);
        }

        $kyc = Kyc::updateOrCreate([
            'user_id' => Auth::id(),
            'category' => 'proof_of_identity',
        ],[
            'proof_type' => $proof_type,
            'kyc_status' => 'pending',
        ]);

        $kyc->clearMediaCollection('front_identity');
        $kyc->clearMediaCollection('back_identity');
        $kyc->clearMediaCollection('passport_identity');

        if ($request->hasFile('front_identity')) {
            $kyc->addMedia($request->file('front_identity'))->toMediaCollection('front_identity');
        }

        if ($request->hasFile('back_identity')) {
            $kyc->addMedia($request->file('back_identity'))->toMediaCollection('back_identity');
        }

        if ($request->hasFile('passport_identity')) {
            $kyc->addMedia($request->file('passport_identity'))->toMediaCollection('passport_identity');
        }

        return back()->with('toast', [
            'title' => trans('public.success'),
            'message' => trans('public.toast_upload_identity_success'),
            'type' => 'success',
        ]);
    }

    /**
     * @throws FileIsTooBig
     * @throws FileDoesNotExist
     */
    public function uploadResidencyProof(Request $request)
    {
        Validator::make($request->all(), [
            'document_type' => ['required'],
            'residency_proof' => ['nullable', 'file'],
        ])->setAttributeNames([
            'document_type' => trans('public.document_type'),
            'residency_proof' => trans('public.residency_proof'),
        ])->validate();

        $proof_type = $request->document_type;

        $exist_kyc = Kyc::where([
            'user_id' => Auth::id(),
            'category' => 'proof_of_residency',
        ])
            ->whereIn('kyc_status', ['verified', 'pending'])
            ->exists();

        if ($exist_kyc) {
            return back()->with('toast', [
                'title' => trans('public.invalid_action'),
                'message' => trans('public.toast_file_submitted_warning'),
                'type' => 'warning',
            ]);
        }

        $kyc = Kyc::updateOrCreate([
            'user_id' => Auth::id(),
            'category' => 'proof_of_residency',
        ],[
            'proof_type' => $proof_type,
            'kyc_status' => 'pending',
        ]);

        if ($request->hasFile('residency_proof')) {
            $kyc->clearMediaCollection('residency_proof');
            $kyc->addMedia($request->file('residency_proof'))->toMediaCollection('residency_proof');
        }

        return back()->with('toast', [
            'title' => trans('public.success'),
            'message' => trans('public.toast_upload_residency_success'),
            'type' => 'success',
        ]);
    }
}
