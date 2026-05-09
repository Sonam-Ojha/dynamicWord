<header class="sticky top-0 z-10 bg-white border-b border-slate-200">
    <div class="h-16 px-4 sm:px-6 flex items-center gap-4">
        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-slate-600 hover:text-slate-900">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>

        <div class="flex-1"></div>

        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="flex items-center gap-2 text-sm">
                @if (auth()->user()->profile_image)
                    <img src="{{ asset('storage/'.auth()->user()->profile_image) }}" class="w-8 h-8 rounded-full object-cover" alt="">
                @else
                    <span class="w-8 h-8 inline-flex items-center justify-center rounded-full bg-indigo-100 text-indigo-700 font-semibold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </span>
                @endif
                <div class="hidden sm:block text-left">
                    <div class="font-medium text-slate-800">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-slate-500">{{ auth()->user()->roles->pluck('name')->join(', ') }}</div>
                </div>
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>

            <div x-show="open" x-cloak @click.outside="open = false"
                 class="absolute right-0 mt-2 w-48 rounded-md bg-white shadow-lg border border-slate-200 py-1 text-sm">
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-slate-700 hover:bg-slate-50">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50">Logout</button>
                </form>
            </div>
        </div>
    </div>
</header>
