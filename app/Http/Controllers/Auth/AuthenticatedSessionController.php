<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login-new');
    }

    /**
     * Handle an incoming authentication request.
     */
   public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();
    $request->session()->regenerate();

    $user = Auth::user(); // user yang login

    // ADMIN & SUPER ADMIN → Dashboard Pentadbir
    if ($user->hasRole('super-admin') || $user->hasRole('admin')) {
        return redirect()->intended(route('admin.dashboard', absolute: false));
    }

    // APPROVAL-ADMIN & STAF/PENGURUSAN → Launcher pengguna (paparan utama grid)
    if (
        $user->hasRole('approval-admin') ||
        $user->hasRole('staff') ||
        $user->hasRole('ketua_unit') ||
        $user->hasRole('penolong_pengarah') ||
        $user->hasRole('ketua_pengarah')
    ) {
        return redirect()->intended(route('staff.launcher', absolute: false));
        // Jika anda mahu terus ke profil ringkas:
        // return redirect()->intended(route('staff.profile', ['user_id' => $user->id, 'page' => 'main'], false));
    }

    // DEFAULT / FALLBACK
    return redirect()->intended(route('dashboard', absolute: false));
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
