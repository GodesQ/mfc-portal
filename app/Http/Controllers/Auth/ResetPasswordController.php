<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetToken;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function viewResetPassword(Request $request)
    {
        $password_reset = PasswordResetToken::where('email', $request->email)
            ->latest()
            ->first();

        if (!Hash::check($request->token, $password_reset->token)) {
            abort(404);
        }

        return view('auth-pass-change-basic', ['email' => $request->email, 'token' => $request->token]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'reset_token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // dd($request->reset_token);

        $email = $request->email;

        // Find the token
        $tokenRecord = PasswordResetToken::where('email', $email)
            ->latest()
            ->first();

        if (!$tokenRecord) {
            return view('error.auth-404-basic');
        }

        if (!Hash::check($request->reset_token, $tokenRecord->token)) {
            return back()->with('fail', value: 'Invalid Token.');
        }

        if (Carbon::parse($tokenRecord->created_at)->addHours(24)->isPast()) {
            return back()->with('fail', 'Token has expired.');
        }

        // Update user password
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        $tokenRecord->delete();

        return redirect()->route('login');
    }
}
