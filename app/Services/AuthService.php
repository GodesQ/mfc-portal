<?php

namespace App\Services;

use App\Models\OTP;
use App\Models\Section;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function login($credentials)
    {
        $user = $this->findUserByCredentials($credentials);

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw new Exception('Invalid Credentials.', 404);
        }

        // dd($user->pa);

        return $user;
    }

    public function register($request)
    {
        $section = Section::find($request->section_id);
        if (!$section) {
            throw new Exception('Section not found.', 404);
        }

        $data = $request->validated();
        $mfc_id_number = generateNewMFCId();
        $role_id = 7; // member role

        $user = User::create(array_merge($data, [
            'password' => Hash::make($request->password),
            'mfc_id_number' => $mfc_id_number,
            'section' => $section->id,
            'role_id' => $role_id,
        ]));

        $user->assignRole('member');

        if (config('auth.verification') === 'email' && $request->has('email')) {
            event(new Registered($user));
        } else {
            $user->sendOTPVerificationNotification();
        }

        return $user;
    }

    public function otpVerify($request)
    {
        $otp = OTP::where('otp_code', $request->otp_code)
            ->where('user_id', $request->user_id)
            ->latest()
            ->first();

        if (!$otp) {
            throw new Exception('OTP Not Found.', 404);
        }

        if ($otp->is_used) {
            throw new Exception('OTP has already been used.', 400);
        }

        if ($otp->expires_at <= Carbon::now()) {
            throw new Exception('Your otp has already expired. Please resend a new OTP.', 400);
        }

        $user = User::where('id', $request->user_id)->first();

        $user->update([
            'contact_number_verified_at' => Carbon::now(),
        ]);

        $otp->update(['is_used' => true]);

        return $user;
    }

    private function findUserByCredentials($credentials)
    {
        return User::where('country_code', $credentials['country_code'] ?? null)
            ->where('contact_number', $credentials['contact_number'] ?? null)
            ->first();
    }
}
