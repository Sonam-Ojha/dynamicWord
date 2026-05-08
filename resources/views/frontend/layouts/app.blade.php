<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Generate Document') - {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased bg-slate-100 text-slate-800 min-h-screen flex flex-col">

    <header class="bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ route('generate.index') }}" class="flex items-center gap-2">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-indigo-600 text-white font-bold">D</span>
                <span class="font-semibold">Bank Doc Gen</span>
            </a>

            <nav class="hidden md:flex items-center gap-6 text-sm">
                <a href="{{ route('generate.index') }}" class="text-slate-700 hover:text-indigo-600">My Dashboard</a>
                <a href="{{ route('generate.banks') }}" class="text-slate-700 hover:text-indigo-600">New Document</a>
                @if (auth()->user()->hasAnyRole(['Super Admin', 'Admin']))
                    <a href="{{ route('admin.dashboard') }}" class="text-slate-700 hover:text-indigo-600">Admin Panel</a>
                @endif
            </nav>

            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center gap-2 text-sm">
                    @if (auth()->user()->profile_image)
                        <img src="{{ asset('storage/'.auth()->user()->profile_image) }}" class="w-8 h-8 rounded-full object-cover" alt="">
                    @else
                        <span class="w-8 h-8 inline-flex items-center justify-center rounded-full bg-indigo-100 text-indigo-700 font-semibold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>
                    @endif
                    <span class="hidden sm:inline font-medium">{{ auth()->user()->name }}</span>
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
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
            <div class="bg-slate-50 border-t border-slate-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
                    @yield('steps')
                </div>
            </div>
        @endif
    </header>

    <main class="flex-1 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8">
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

    <footer class="px-6 py-4 text-center text-xs text-slate-500 bg-white border-t border-slate-200">
        &copy; {{ date('Y') }} {{ config('app.name') }}.
    </footer>

    @stack('scripts')
</body>
</html>
