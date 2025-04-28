<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Distributor;
use App\Models\Retailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'all');
        
        // Get users from the users table (including admins and processors)
        $query = User::query();
        
        // Filter users based on type
        if ($type !== 'all') {
            $query->where('role', strtolower($type));
        }
        
        $users = $query->latest()->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => ucfirst($user->role),
                'status' => $user->is_active ? 'Active' : 'Inactive',
                'joined' => $user->created_at->format('M d, Y'),
                'model_type' => 'user'
            ];
        })->toArray();
        
        // Get farmers
        if ($type === 'all' || $type === 'farmer') {
            $farmers = \App\Models\Farmer::select([
                'id',
                'name',
                'email',
                'created_at',
                'active'
            ])->get()->map(function ($farmer) {
                return [
                    'id' => $farmer->id,
                    'name' => $farmer->name,
                    'email' => $farmer->email,
                    'role' => 'Farmer',
                    'status' => $farmer->active ? 'Active' : 'Inactive',
                    'joined' => $farmer->created_at->format('M d, Y'),
                    'model_type' => 'farmer'
                ];
            })->toArray();
            
            $users = array_merge($users, $farmers);
        }
        
        // Get distributors
        if ($type === 'all' || $type === 'distributor') {
            $distributors = Distributor::select([
                'id',
                'name',
                'email',
                'created_at',
                'active'
            ])->get()->map(function ($distributor) {
                return [
                    'id' => $distributor->id,
                    'name' => $distributor->name,
                    'email' => $distributor->email,
                    'role' => 'Distributor',
                    'status' => $distributor->active ? 'Active' : 'Inactive',
                    'joined' => $distributor->created_at->format('M d, Y'),
                    'model_type' => 'distributor'
                ];
            })->toArray();
            
            $users = array_merge($users, $distributors);
        }
        
        // Get retailers
        if ($type === 'all' || $type === 'retailer') {
            $retailers = Retailer::select([
                'id',
                'name',
                'email',
                'created_at',
                'status'
            ])->get()->map(function ($retailer) {
                return [
                    'id' => $retailer->id,
                    'name' => $retailer->name,
                    'email' => $retailer->email,
                    'role' => 'Retailer',
                    'status' => $retailer->status ?? 'Active',
                    'joined' => $retailer->created_at->format('M d, Y'),
                    'model_type' => 'retailer'
                ];
            })->toArray();
            
            $users = array_merge($users, $retailers);
        }
        
        // Convert to collection for sorting
        $users = collect($users)->sortByDesc('joined');
        
        // Paginate the results
        $perPage = 10;
        $page = $request->get('page', 1);
        $items = $users->forPage($page, $perPage);
        
        $users = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $users->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:admin,farmer,processor,distributor,retailer'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => strtolower($request->role),
            'is_active' => true,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required', 'string', 'in:admin,farmer,processor,distributor,retailer'],
            'is_active' => ['required', 'boolean'],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => strtolower($request->role),
            'is_active' => $request->is_active,
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['confirmed', Rules\Password::defaults()],
            ]);

            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
} 