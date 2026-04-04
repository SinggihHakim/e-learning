<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    protected \App\Services\AdminUserService $adminUserService;

    public function __construct(\App\Services\AdminUserService $adminUserService)
    {
        $this->adminUserService = $adminUserService;
    }

    public function index(Request $request)
    {
        $query = User::query();
        
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        
        $users = $query->latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(\App\Http\Requests\StoreUserRequest $request)
    {
        $this->adminUserService->createUser($request->validated());
        return redirect()->route('admin.users.index')->with('success', __('Pengguna berhasil dibuat.'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(\App\Http\Requests\UpdateUserRequest $request, User $user)
    {
        $this->adminUserService->updateUser($user, $request->validated());
        return redirect()->route('admin.users.index')->with('success', __('Pengguna berhasil diperbarui.'));
    }

    public function destroy(User $user)
    {
        try {
            $this->adminUserService->deleteUser($user, auth()->id());
            return redirect()->route('admin.users.index')->with('success', __('Pengguna berhasil dihapus.'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        $role = $request->role;
        $fileName = 'UsersExport_' . date('Ymd_His') . '.xlsx';
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\UsersExport($role), $fileName);
    }
}
