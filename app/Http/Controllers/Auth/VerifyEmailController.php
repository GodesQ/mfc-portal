<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OTP;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        // dd($request->all());

        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('users.profile');
        }

        $user = Auth::user();

        $otp = OTP::where('user_id', $user->id)
            ->where('otp_code', $request->otp)
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if ($otp) {
            $otp->is_used = 1;
            $otp->save();

            $user->email_verified_at = Carbon::now();
            $user->save();

            return redirect()->route('users.profile');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->route('users.profile');
    }

    public function notice()
    {
        if (Auth::user()->hasVerifiedEmail()) {
            return redirect()->route('users.profile');
        }
        
        return view('auth.verify');
    }
}
