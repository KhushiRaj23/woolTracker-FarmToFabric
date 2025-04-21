<?php

namespace App\Http\Controllers\Distributor\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\DistributorLoginRequest;
use App\Models\Distributor;
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
        return view('distributor.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(DistributorLoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('distributor.dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('distributor')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
