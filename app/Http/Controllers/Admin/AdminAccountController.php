<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AdminAccountController extends Controller
{
    public function index(): View
    {
        $admins = User::whereIn('role', ['admin', 'super_admin'])
            ->orderBy('name')
            ->get();

        return view('admin.accounts', ['admins' => $admins]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        User::create([...$data, 'role' => 'admin']);

        return back()->with('success', 'Admin account created.');
    }
}
