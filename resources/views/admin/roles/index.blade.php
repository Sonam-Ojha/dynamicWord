@extends('admin.layouts.app')

@section('title', 'Roles')
@section('page_title', 'Roles & Permissions')

@section('page_actions')
    <x-admin.button :href="route('admin.roles.create')">+ New Role</x-admin.button>
@endsection

@section('content')
    <x-admin.card>
        <div class="mb-4">
            <x-admin.search-bar :action="route('admin.roles.index')" placeholder="Search roles..." />
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-slate-600 text-xs uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">Role</th>
                        <th class="px-4 py-3 text-left">Permissions</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($roles as $role)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 font-medium">{{ $role->name }}</td>
                            <td class="px-4 py-3">
                                @foreach ($role->permissions as $perm)
                                    <span class="inline-flex rounded-full bg-slate-100 text-slate-700 text-xs px-2 py-0.5 mr-1 mb-1">{{ $perm->name }}</span>
                                @endforeach
                            </td>
                            <td class="px-4 py-3 text-right space-x-3">
                                <a href="{{ route('admin.roles.edit', $role) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">Edit</a>
                                @if (! in_array($role->name, ['Super Admin', 'Admin', 'Operator'], true))
                                    <x-admin.delete-form :action="route('admin.roles.destroy', $role)" />
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-4 py-8 text-center text-slate-500">No roles.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $roles->links() }}</div>
    </x-admin.card>
@endsection
