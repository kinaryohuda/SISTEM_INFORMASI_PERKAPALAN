<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SuperAdminAdminManagementController extends Controller
{
    public function index()
    {
        $admins = User::where('role', 'admin')->paginate(10);
        return view('main.super-admin.pengguna.admin.index-pengguna-admin', compact('admins'));
    }

    public function create()
    {
        return view('main.super-admin.pengguna.admin.create-pengguna-admin');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'nik'      => 'nullable|string|max:20',
            'no_hp'    => 'nullable|string|max:20',
            'alamat'   => 'nullable|string|max:255',
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => 'admin',
            'nik'      => $validated['nik'] ?? null,
            'no_hp'    => $validated['no_hp'] ?? null,
            'alamat'   => $validated['alamat'] ?? null,
        ]);

        return redirect()->route('superAdmin.pengguna-admin-index')->with('success', 'Admin berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);
        return view('main.super-admin.pengguna.admin.edit-pengguna-admin', compact('admin'));
    }

    public function update(Request $request, $id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);

        $validated = $request->validate([
            'name'  => 'required',
            'email' => [
                'required',
                Rule::unique('users')->ignore($admin->id),
            ],
            'nik'      => 'nullable|string|max:20',
            'no_hp'    => 'nullable|string|max:20',
            'alamat'   => 'nullable|string|max:255',
            'password' => 'nullable|min:6',
        ]);

        if ($request->password) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        $admin->update($validated);

        return redirect()->route('superAdmin.pengguna-admin-index')->with('success', 'Admin berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);
        $admin->delete();

        return back()->with('success', 'Admin berhasil dihapus.');
    }
}
