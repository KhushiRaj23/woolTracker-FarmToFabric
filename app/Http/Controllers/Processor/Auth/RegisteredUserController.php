<?php

namespace App\Http\Controllers\Processor\Auth;

use App\Http\Controllers\Controller;
use App\Models\Processor;
use App\Models\User;

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
        return view('processor.auth.register');
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
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'company_name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'phone' => ['required', 'string', 'max:20'],
            'capacity' => ['required', 'integer', 'min:1'],
            'specialization' => ['required', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'processor',
        ]);

        $processor = Processor::create([
            'user_id' => $user->id,
            'company_name' => $request->company_name,
            'address' => $request->address,
            'phone' => $request->phone,
            'capacity' => $request->capacity,
            'specialization' => $request->specialization,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('processor.dashboard', absolute: false));
    }
}
