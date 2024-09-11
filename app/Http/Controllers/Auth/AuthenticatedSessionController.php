<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {   
        $loginType = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Attempt to authenticate the user with either email or username
        if (Auth::attempt([$loginType => $request->input('login'), 'password' => $request->input('password')])) {
            $request->session()->regenerate();
    
            return redirect()->intended(RouteServiceProvider::HOME);
        }
    
        // Authentication failed
        return back()->withErrors([
            'login' => 'The provided credentials do not match our records.',
        ]);
    
        // $request->authenticate();

        // $request->session()->regenerate();

        // return redirect()->intended(RouteServiceProvider::HOME);
    }

    public function create()
    {
        return view('auth.login');
    }

    public function reset_password()
    {
        return view('auth-pass-reset-basic');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
