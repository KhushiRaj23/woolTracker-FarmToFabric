<?php

namespace App\Http\Controllers\Distributor\Auth;

use App\Http\Controllers\Controller;
use App\Models\Distributor;
use App\Models\Processor;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('distributor.auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.Distributor::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $distributor = Distributor::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($distributor));

        Auth::guard('distributor')->login($distributor);

        return redirect(route('distributor.dashboard', absolute: false));
    }
}
