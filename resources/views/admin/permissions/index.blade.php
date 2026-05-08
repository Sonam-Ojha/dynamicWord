@extends('admin.layouts.app')

@section('title', 'Permissions')
@section('page_title', 'Permissions')

@section('page_actions')
    <x-admin.button :href="route('admin.permissions.create')">+ New Permission</x-admin.button>
@endsection

@section('content')
    <x-admin.card>
        <div class="mb-4">
            <x-admin.search-bar :action="route('admin.permissions.index')" placeholder="Search permissions..." />
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-slate-600 text-xs uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">Permission</th>
                        <th class="px-4 py-3 text-left">Guard</th>
                        <th class="px-4 py-3 text-left">Roles</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($permissions as $permission)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 font-medium">{{ $permission->name }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $permission->guard_name }}</td>
                            <td class="px-4 py-3">
                                @forelse ($permission->roles as $role)
                                    <span class="inline-flex rounded-full bg-indigo-50 text-indigo-700 text-xs px-2 py-0.5 mr-1 mb-1">{{ $role->name }}</span>
                                @empty
                                    <span class="text-xs text-slate-400">— not assigned —</span>
                                @endforelse
                            </td>
                            <td class="px-4 py-3 text-right space-x-3">
                                <a href="{{ route('admin.permissions.edit', $permission) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">Edit</a>
                                @if (! in_array($permission->name, ['view dashboard', 'manage roles', 'manage permissions'], true))
                                    <x-admin.delete-form :action="route('admin.permissions.destroy', $permission)" />
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-8 text-center text-slate-500">No permissions.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $permissions->links() }}</div>
    </x-admin.card>
@endsection
