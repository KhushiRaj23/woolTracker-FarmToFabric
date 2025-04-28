<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Distributor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class DistributorController extends Controller
{
    public function edit(Distributor $distributor)
    {
        return view('admin.distributors.edit', compact('distributor'));
    }

    public function update(Request $request, Distributor $distributor)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:distributors,email,' . $distributor->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'country' => ['nullable', 'string', 'max:255'],
            'active' => ['required', 'boolean'],
        ]);

        $distributor->update($request->all());

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['confirmed', Rules\Password::defaults()],
            ]);

            $distributor->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Distributor updated successfully.');
    }

    public function destroy(Distributor $distributor)
    {
        $distributor->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'Distributor deleted successfully.');
    }
} 