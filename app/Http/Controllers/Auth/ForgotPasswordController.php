<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetMail;
use App\Models\PasswordResetToken;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    // public function sendResetLinkEmail(Request $request)
    // {

    // }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email']
        ]);

        $token = Str::random(60);
        $email = $request->email;

        // Delete old tokens for this email
        PasswordResetToken::where('email', $email)->delete();

        // Create new token
        PasswordResetToken::create([
            'email' => $email,
            'token' => Hash::make($token),
            'created_at' => now()
        ]);

        // Send email
        $resetLink = url('') . '/password-reset?token=' . $token . '&email=' . urlencode($email);
        Mail::to($email)->send(new PasswordResetMail($resetLink));

        return response()->json([
            'status' => 'success',
            'message' => 'Password reset link sent to your email',
        ]);
    }

    /**
     * Get the response for a successful password reset link for API.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkResponseApi(Request $request, $response)
    {
        return response()->json([
            'status' => trans($response),
            'message' => 'Password reset email sent successfully'
        ]);
    }
}
