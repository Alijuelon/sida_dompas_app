<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Kader;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class UserManagementController extends Controller
{
    public function index(): Response
    {
        $users = User::where('role', '!=', 'admin')
            ->with(['kader'])
            ->orderBy('name')
            ->get();

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Users/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users,username|alpha_dash',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => ['required', Rule::in(['kader', 'kades'])],
            'status' => 'boolean',
            'nama_kader' => 'nullable|string|max:255',
            'wilayah' => 'nullable|string|max:255',
            'rt' => 'nullable|string|max:10',
            'rw' => 'nullable|string|max:10',
            'no_hp' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'status' => $validated['status'] ?? true,
        ]);

        if ($validated['role'] === 'kader') {
            Kader::create([
                'user_id' => $user->id,
                'nama_kader' => $validated['nama_kader'] ?? $validated['name'],
                'wilayah' => $validated['wilayah'] ?? null,
                'rt' => $validated['rt'] ?? null,
                'rw' => $validated['rw'] ?? null,
                'no_hp' => $validated['no_hp'] ?? null,
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user): Response
    {
        $user->load('kader');

        return Inertia::render('Admin/Users/Edit', [
            'user' => $user,
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:50', 'alpha_dash', Rule::unique('users')->ignore($user->id)],
            'email' => ['nullable', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
            'role' => ['required', Rule::in(['kader', 'kades'])],
            'status' => 'boolean',
            'nama_kader' => 'nullable|string|max:255',
            'wilayah' => 'nullable|string|max:255',
            'rt' => 'nullable|string|max:10',
            'rw' => 'nullable|string|max:10',
            'no_hp' => 'nullable|string|max:20',
        ]);

        $user->update([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'] ?? null,
            'role' => $validated['role'],
            'status' => $validated['status'] ?? true,
        ]);

        if (!empty($validated['password'])) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        if ($validated['role'] === 'kader') {
            if ($user->kader) {
                $user->kader->update([
                    'nama_kader' => $validated['nama_kader'] ?? $user->name,
                    'wilayah' => $validated['wilayah'] ?? null,
                    'rt' => $validated['rt'] ?? null,
                    'rw' => $validated['rw'] ?? null,
                    'no_hp' => $validated['no_hp'] ?? null,
                ]);
            } else {
                Kader::create([
                    'user_id' => $user->id,
                    'nama_kader' => $validated['nama_kader'] ?? $user->name,
                    'wilayah' => $validated['wilayah'] ?? null,
                    'rt' => $validated['rt'] ?? null,
                    'rw' => $validated['rw'] ?? null,
                    'no_hp' => $validated['no_hp'] ?? null,
                ]);
            }
        } elseif ($user->kader) {
            $user->kader->delete();
        }

        return redirect()->route('admin.users.index')->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Tidak dapat menghapus akun Admin.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        $user->update(['status' => ! $user->status]);

        return redirect()->back()->with('success', 'Status user berhasil diubah.');
    }
}
