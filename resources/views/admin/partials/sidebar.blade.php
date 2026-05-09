@php
    $navItems = [
        ['route' => 'admin.dashboard',  'label' => 'Dashboard',   'permission' => 'view dashboard',        'icon' => 'home'],
        ['route' => 'admin.banks.index','label' => 'Banks',       'permission' => 'manage banks',          'icon' => 'building'],
        ['route' => 'admin.bank-branches.index','label' => 'Bank Branches', 'permission' => 'manage bank branches', 'icon' => 'building'],
        ['route' => 'admin.templates.index',  'label' => 'Templates',  'permission' => 'manage templates',  'icon' => 'document'],
        ['route' => 'admin.documents.index',  'label' => 'Documents',  'permission' => 'manage documents',  'icon' => 'document-text'],
        ['route' => 'admin.download-logs.index', 'label' => 'Download Logs', 'permission' => 'view download logs', 'icon' => 'document-text'],
        ['route' => 'admin.signatures.index', 'label' => 'Signatures', 'permission' => 'manage signatures', 'icon' => 'pencil'],
        ['route' => 'admin.users.index', 'label' => 'Users', 'permission' => 'manage users', 'icon' => 'users'],
        ['route' => 'admin.roles.index', 'label' => 'Roles', 'permission' => 'manage roles', 'icon' => 'shield'],
        ['route' => 'admin.permissions.index', 'label' => 'Permissions', 'permission' => 'manage permissions', 'icon' => 'shield'],
    ];
@endphp

<aside
    class="fixed inset-y-0 left-0 z-30 w-64 bg-slate-900 text-slate-200 transform transition-transform duration-200 lg:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
>
    <div class="h-16 flex items-center px-6 border-b border-slate-800">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
            <span class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-indigo-500 text-white font-bold">D</span>
            <span class="font-semibold text-white">Bank Doc Gen</span>
        </a>
        <button @click="sidebarOpen = false" class="lg:hidden ml-auto text-slate-400 hover:text-white">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    <nav class="px-3 py-4 space-y-1 overflow-y-auto h-[calc(100vh-4rem)]">
        @foreach ($navItems as $item)
            @can($item['permission'])
                @php $active = request()->routeIs(str_replace('.index', '*', $item['route'])) || request()->routeIs($item['route']); @endphp
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition
                          {{ $active ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <x-admin.icon :name="$item['icon']" class="w-5 h-5" />
                    <span>{{ $item['label'] }}</span>
                </a>
            @endcan
        @endforeach
    </nav>
</aside>

<div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 z-20 bg-black/40 lg:hidden"></div>
