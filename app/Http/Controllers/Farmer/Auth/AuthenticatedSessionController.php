<?php

namespace App\Http\Controllers\Farmer\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\FarmerLoginRequest;
use App\Models\Farmer;
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
        return view('farmer.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(FarmerLoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('farmer.dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('farmer')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
