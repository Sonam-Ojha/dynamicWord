@extends('admin.layouts.app')

@section('title', 'Users')
@section('page_title', 'Users')

@section('page_actions')
    <x-admin.button :href="route('admin.users.create')">+ New User</x-admin.button>
@endsection

@section('content')
    <x-admin.card>
        <div class="mb-4">
            <x-admin.search-bar :action="route('admin.users.index')" placeholder="Search users..." />
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-slate-600 text-xs uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">User</th>
                        <th class="px-4 py-3 text-left">Email</th>
                        <th class="px-4 py-3 text-left">Phone</th>
                        <th class="px-4 py-3 text-left">Roles</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($users as $user)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 flex items-center gap-3">
                                @if ($user->profile_image)
                                    <img src="{{ asset('storage/'.$user->profile_image) }}" class="w-9 h-9 rounded-full object-cover" alt="">
                                @else
                                    <div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-semibold">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                                <span class="font-medium">{{ $user->name }}</span>
                            </td>
                            <td class="px-4 py-3">{{ $user->email }}</td>
                            <td class="px-4 py-3">{{ $user->phone }}</td>
                            <td class="px-4 py-3">
                                @foreach ($user->roles as $role)
                                    <span class="inline-flex rounded-full bg-indigo-50 text-indigo-700 text-xs px-2 py-0.5 mr-1">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td class="px-4 py-3">
                                <x-admin.status-toggle :action="route('admin.users.toggle-status', $user)" :active="(bool) $user->status" />
                            </td>
                            <td class="px-4 py-3 text-right space-x-3">
                                <a href="{{ route('admin.users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">Edit</a>
                                @if ($user->id !== auth()->id())
                                    <x-admin.delete-form :action="route('admin.users.destroy', $user)" />
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-8 text-center text-slate-500">No users.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $users->links() }}</div>
    </x-admin.card>
@endsection
