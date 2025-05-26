<?php

namespace App\Services;

use App\Models\Section;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function login($request, $credentials)
    {
        try {
            $user = User::where('country_code', $credentials['country_code'])
                ->where('contact_number', $credentials['contact_number'])
                ->first();

            if (!$user) {
                throw new Exception('Invalid Credentials.', 404);
            }

            if (!Hash::check($credentials['password'], $user->password)) {
                throw new Exception('Invalid Credentials.', 404);
            }

            return $user;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function register($request)
    {
        $section = Section::where('name', $request->section)->first();
        $data = $request->validated();
        $mfc_id_number = generateNewMFCId();
        $role_id = 7; // member role

        $user = User::create(array_merge($data, [
            'mfc_id_number' => $mfc_id_number,
            'section' => $section->id,
            'role_id' => $role_id,
        ]));

        $user->assignRole('member');

        if (config('auth.verification') === 'email') {
            event(new Registered($user));
        } else {
            $user->sendOTPVerificationNotification();
        }
    }
}
