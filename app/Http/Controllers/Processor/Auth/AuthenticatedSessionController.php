<?php

namespace App\Http\Controllers\Processor\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ProcessorLoginRequest;
use App\Models\Processor;
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
        return view('processor.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(ProcessorLoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('processor.dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('processor')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
