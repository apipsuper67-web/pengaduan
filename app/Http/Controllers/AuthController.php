<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);
        $key = Str::lower($validated['email']).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            return back()->withErrors([
                'email' => "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik.",
            ])->onlyInput('email');
        }

        if (! Auth::attempt($validated, $request->boolean('remember'))) {
            RateLimiter::hit($key, 60);

            return back()->withErrors([
                'email' => 'Email atau kata sandi tidak sesuai.',
            ])->onlyInput('email');
        }

        RateLimiter::clear($key);
        $request->session()->regenerate();

        return redirect()->intended(route($request->user()->dashboardRoute()));
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'no_whatsapp' => ['required', 'string', 'max:30'],
            'alamat' => ['required', 'string', 'max:1000'],
            'password' => ['required', 'confirmed', Password::min(10)],
        ]);

        $user = User::create($validated);
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('warga.dashboard')->with('success', 'Akun berhasil dibuat. Selamat datang!');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
