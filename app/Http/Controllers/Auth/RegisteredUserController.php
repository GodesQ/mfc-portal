<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\MemberRegisterRequest;
use App\Models\Section;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    public function index()
    {
        return view('auth.register');
    }
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(MemberRegisterRequest $request) : RedirectResponse
    {
        $section = Section::where('name', $request->section)->first();

        $user = User::create([
            'mfc_id_number' => generateNewMFCId(),
            'username' => $request->username,
            'email' => $request->email,
            'first_name' => $request->firstname,
            'last_name' => $request->lastname,
            'password' => Hash::make($request->password),
            'section_id' => $section->id,
            'contact_number' => $request->contact_number,
            'role_id' => 7,
        ]);

        $user->assignRole('member');

        event(new Registered($user));

        Auth::login($user);

        // $user->sendEmailVerificationNotification();

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
