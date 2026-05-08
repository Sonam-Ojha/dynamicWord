<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    private const PROTECTED_PERMISSIONS = [
        'view dashboard',
        'manage roles',
        'manage permissions',
    ];

    public function index(Request $request): View
    {
        $permissions = Permission::query()
            ->with('roles')
            ->when($request->input('q'), fn ($q, $v) => $q->where('name', 'like', "%{$v}%"))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.permissions.index', compact('permissions'));
    }

    public function create(): View
    {
        $roles = Role::orderBy('name')->get();
        return view('admin.permissions.create', compact('roles'));
    }

    public function store(PermissionRequest $request): RedirectResponse
    {
        $permission = Permission::create([
            'name' => $request->input('name'),
            'guard_name' => $request->input('guard_name') ?: 'web',
        ]);

        if ($roleNames = $request->input('roles', [])) {
            $roles = Role::whereIn('name', $roleNames)->get();
            foreach ($roles as $role) {
                $role->givePermissionTo($permission);
            }
        }

        return redirect()->route('admin.permissions.index')->with('success', 'Permission created.');
    }

    public function edit(Permission $permission): View
    {
        $roles = Role::orderBy('name')->get();
        $permissionRoles = $permission->roles->pluck('name')->toArray();

        return view('admin.permissions.edit', compact('permission', 'roles', 'permissionRoles'));
    }

    public function update(PermissionRequest $request, Permission $permission): RedirectResponse
    {
        if (in_array($permission->name, self::PROTECTED_PERMISSIONS, true)
            && $request->input('name') !== $permission->name) {
            return back()->with('error', 'Core permissions cannot be renamed.');
        }

        $permission->update([
            'name' => $request->input('name'),
            'guard_name' => $request->input('guard_name') ?: $permission->guard_name,
        ]);

        $newRoleNames = $request->input('roles', []);
        $allRoles = Role::all();

        foreach ($allRoles as $role) {
            if (in_array($role->name, $newRoleNames, true)) {
                $role->givePermissionTo($permission);
            } else {
                $role->revokePermissionTo($permission);
            }
        }

        return redirect()->route('admin.permissions.index')->with('success', 'Permission updated.');
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        if (in_array($permission->name, self::PROTECTED_PERMISSIONS, true)) {
            return back()->with('error', 'Core permissions cannot be deleted.');
        }

        $permission->delete();

        return redirect()->route('admin.permissions.index')->with('success', 'Permission deleted.');
    }
}
