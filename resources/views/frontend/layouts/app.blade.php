<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .fe-sidebar {
            position: fixed;
            top: 0; left: 0; bottom: 0;
            width: 240px;
            background: #0f172a;
            color: #cbd5e1;
            z-index: 30;
            transform: translateX(-100%);
            transition: transform 0.2s;
            overflow-y: auto;
        }
        .fe-sidebar.is-open { transform: translateX(0); }
        @media (min-width: 1024px) {
            .fe-sidebar { transform: translateX(0); }
        }
        .fe-main { padding-left: 0; }
        @media (min-width: 1024px) {
            .fe-main { padding-left: 240px; }
        }
        .fe-nav-link {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.625rem 0.875rem;
            border-radius: 0.5rem;
            color: #cbd5e1;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: background-color 0.15s, color 0.15s;
        }
        .fe-nav-link:hover { background: #1e293b; color: white; }
        .fe-nav-link.active { background: #4f46e5; color: white; }
    </style>
    @stack('styles')
</head>
<body class="font-sans antialiased bg-slate-100 text-slate-800 min-h-screen">

    <div x-data="{ sidebarOpen: false }">

        {{-- Sidebar --}}
        <aside class="fe-sidebar" :class="{ 'is-open': sidebarOpen }">
            <div class="h-16 flex items-center px-5 border-b border-slate-800">
                <a href="{{ route('generate.index') }}" class="flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-indigo-600 text-white font-bold">D</span>
                    <span class="font-semibold text-white">Bank Doc Gen</span>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden ml-auto text-slate-400 hover:text-white">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <nav class="px-3 py-4 space-y-1">
                <a href="{{ route('generate.index') }}"
                   class="fe-nav-link {{ request()->routeIs('generate.index') ? 'active' : '' }}">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('generate.myDocuments') }}"
                   class="fe-nav-link {{ request()->routeIs('generate.myDocuments') ? 'active' : '' }}">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5l5 5v11a2 2 0 01-2 2z"/></svg>
                    <span>My Documents</span>
                </a>

                <a href="{{ route('generate.banks') }}"
                   class="fe-nav-link {{ request()->routeIs('generate.banks') || request()->routeIs('generate.templates') || request()->routeIs('generate.form') ? 'active' : '' }}">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    <span>New Document</span>
                </a>

                <div class="pt-4 mt-4 border-t border-slate-800">
                    <div class="px-3 text-xs uppercase text-slate-500 mb-2">Account</div>
                    <a href="{{ route('profile.edit') }}" class="fe-nav-link">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span>Profile</span>
                    </a>
                    @if (auth()->user()->hasAnyRole(['Super Admin', 'Admin']))
                        <a href="{{ route('admin.dashboard') }}" class="fe-nav-link">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            <span>Admin Panel</span>
                        </a>
                    @endif
                </div>
            </nav>
        </aside>

        {{-- Sidebar overlay (mobile) --}}
        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
             class="fixed inset-0 bg-black/40 z-20 lg:hidden"></div>

        {{-- Main content area --}}
        <div class="fe-main min-h-screen flex flex-col">

            {{-- Top bar --}}
            <header class="bg-white border-b border-slate-200 sticky top-0 z-10">
                <div class="px-4 sm:px-6 h-16 flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="lg:hidden text-slate-600">
                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>

                    <div class="flex-1"></div>

                    <a href="{{ route('generate.banks') }}"
                       class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-md bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
                        + New Document
                    </a>

                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center gap-2 text-sm">
                            @if (auth()->user()->profile_image)
                                <img src="{{ asset('storage/'.auth()->user()->profile_image) }}" class="w-8 h-8 rounded-full object-cover" alt="">
                            @else
                                <span class="w-8 h-8 inline-flex items-center justify-center rounded-full bg-indigo-100 text-indigo-700 font-semibold">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </span>
                            @endif
                            <span class="hidden sm:block font-medium text-slate-800">{{ auth()->user()->name }}</span>
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" x-cloak @click.outside="open = false"
                             class="absolute right-0 mt-2 w-48 rounded-md bg-white shadow-lg border border-slate-200 py-1 text-sm z-20">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-slate-700 hover:bg-slate-50">Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>

                @hasSection('steps')
                    <div class="bg-slate-50 border-t border-slate-200 px-4 sm:px-6 py-3">
                        @yield('steps')
                    </div>
                @endif
            </header>

            <main class="flex-1 px-4 sm:px-6 py-6">
                @if (session('success'))
                    <div class="mb-4 rounded-md bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-4 rounded-md bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>

            <footer class="px-6 py-4 text-center text-xs text-slate-500 border-t border-slate-200 bg-white">
                &copy; {{ date('Y') }} {{ config('app.name') }}.
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
