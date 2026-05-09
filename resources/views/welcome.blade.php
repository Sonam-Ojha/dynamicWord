<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Bank Doc Gen') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-slate-800 bg-white">

    {{-- Header --}}
    <header class="border-b border-slate-200 bg-white sticky top-0 z-10">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-indigo-600 text-white font-bold text-sm">D</span>
                <span class="font-semibold text-slate-900">Bank Doc Gen</span>
            </a>
            <nav class="flex items-center gap-3 text-sm">
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-4 py-2 rounded-md bg-indigo-600 text-white font-medium hover:bg-indigo-700">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 text-slate-700 hover:text-indigo-600 font-medium">Login</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-4 py-2 rounded-md bg-indigo-600 text-white font-medium hover:bg-indigo-700">Register</a>
                    @endif
                @endauth
            </nav>
        </div>
    </header>

    {{-- Hero --}}
    <section class="bg-slate-50 py-20">
        <div class="max-w-3xl mx-auto px-6 text-center">
            <h1 class="text-4xl sm:text-5xl font-bold text-slate-900 leading-tight tracking-tight">
                Generate bank documents
                <br>
                <span class="text-indigo-600">in minutes, not hours</span>
            </h1>
            <p class="mt-6 text-lg text-slate-600 leading-relaxed">
                Build templates once. Operators fill simple forms — the system auto-generates,
                previews and prints customer-ready documents on demand.
            </p>

            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-6 py-3 rounded-md bg-indigo-600 text-white font-semibold hover:bg-indigo-700 shadow-sm">
                        Open Dashboard →
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-6 py-3 rounded-md bg-indigo-600 text-white font-semibold hover:bg-indigo-700 shadow-sm">
                        Get Started →
                    </a>
                    <a href="#features" class="px-6 py-3 rounded-md bg-white border border-slate-300 text-slate-700 font-medium hover:bg-slate-50">
                        See how it works
                    </a>
                @endauth
            </div>
        </div>
    </section>

    {{-- Features --}}
    <section id="features" class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-slate-900">Built for banking workflows</h2>
                <p class="mt-3 text-slate-600">Design once, generate thousands.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                <div class="rounded-lg border border-slate-200 p-6 hover:border-indigo-300 hover:shadow-sm transition">
                    <div class="inline-flex items-center justify-center w-10 h-10 rounded-md bg-indigo-50 text-indigo-600 mb-4">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-slate-900 text-lg">Auto-Detect Fields</h3>
                    <p class="text-sm text-slate-600 mt-2 leading-relaxed">Paste HTML with placeholders — fields are auto-created with smart type detection.</p>
                </div>

                <div class="rounded-lg border border-slate-200 p-6 hover:border-indigo-300 hover:shadow-sm transition">
                    <div class="inline-flex items-center justify-center w-10 h-10 rounded-md bg-indigo-50 text-indigo-600 mb-4">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-slate-900 text-lg">Live Preview</h3>
                    <p class="text-sm text-slate-600 mt-2 leading-relaxed">Document updates in real-time as the operator fills the form.</p>
                </div>

                <div class="rounded-lg border border-slate-200 p-6 hover:border-indigo-300 hover:shadow-sm transition">
                    <div class="inline-flex items-center justify-center w-10 h-10 rounded-md bg-indigo-50 text-indigo-600 mb-4">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-slate-900 text-lg">Draft & Resume</h3>
                    <p class="text-sm text-slate-600 mt-2 leading-relaxed">Save half-filled forms and resume from your dashboard later.</p>
                </div>

                <div class="rounded-lg border border-slate-200 p-6 hover:border-indigo-300 hover:shadow-sm transition">
                    <div class="inline-flex items-center justify-center w-10 h-10 rounded-md bg-indigo-50 text-indigo-600 mb-4">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-slate-900 text-lg">Role-Based Access</h3>
                    <p class="text-sm text-slate-600 mt-2 leading-relaxed">Super Admin, Admin, Operator — fully customizable permissions.</p>
                </div>

                <div class="rounded-lg border border-slate-200 p-6 hover:border-indigo-300 hover:shadow-sm transition">
                    <div class="inline-flex items-center justify-center w-10 h-10 rounded-md bg-indigo-50 text-indigo-600 mb-4">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-slate-900 text-lg">Audit Log</h3>
                    <p class="text-sm text-slate-600 mt-2 leading-relaxed">Every download tracked — user, IP and timestamp captured.</p>
                </div>

                <div class="rounded-lg border border-slate-200 p-6 hover:border-indigo-300 hover:shadow-sm transition">
                    <div class="inline-flex items-center justify-center w-10 h-10 rounded-md bg-indigo-50 text-indigo-600 mb-4">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M5 21V7l7-4 7 4v14M9 9h.01M9 13h.01M9 17h.01M15 9h.01M15 13h.01M15 17h.01"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-slate-900 text-lg">Multi-Bank</h3>
                    <p class="text-sm text-slate-600 mt-2 leading-relaxed">Manage templates across multiple banks and categories.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- How it works --}}
    <section class="py-20 bg-slate-50 border-t border-slate-200">
        <div class="max-w-5xl mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-slate-900">How it works</h2>
                <p class="mt-3 text-slate-600">Three simple steps from setup to printed document.</p>
            </div>

            <div class="flex flex-col sm:flex-row gap-5 items-stretch">
                <div class="flex-1 bg-white border border-slate-200 rounded-lg p-6 relative">

                    <div class="text-xs font-bold text-indigo-600 tracking-wider mt-2">SETUP</div>
                    <h3 class="font-semibold text-slate-900 text-lg mt-1">Setup template</h3>
                    <p class="text-sm text-slate-600 mt-2 leading-relaxed">Admin pastes HTML with placeholders. Auto-detect creates fields in one click.</p>
                </div>

                <div class="hidden sm:flex items-center justify-center text-indigo-300">
                    <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>

                <div class="flex-1 bg-white border border-slate-200 rounded-lg p-6 relative">

                    <div class="text-xs font-bold text-indigo-600 tracking-wider mt-2">FILL</div>
                    <h3 class="font-semibold text-slate-900 text-lg mt-1">Fill the form</h3>
                    <p class="text-sm text-slate-600 mt-2 leading-relaxed">Operator picks bank and template, fills the form. Live preview as you type.</p>
                </div>

                <div class="hidden sm:flex items-center justify-center text-indigo-300">
                    <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>

                <div class="flex-1 bg-white border border-slate-200 rounded-lg p-6 relative">

                    <div class="text-xs font-bold text-indigo-600 tracking-wider mt-2">FINISH</div>
                    <h3 class="font-semibold text-slate-900 text-lg mt-1">Print or download</h3>
                    <p class="text-sm text-slate-600 mt-2 leading-relaxed">Finalize the document. Print directly or download — every action is audited.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-16 bg-indigo-600 text-white">
        <div class="max-w-3xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold">Ready to streamline your bank documents?</h2>
            <p class="mt-3 text-indigo-100">Login as Super Admin to set up templates, or as Operator to start generating.</p>

            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-6 py-3 rounded-md bg-white text-indigo-700 font-semibold hover:bg-indigo-50 shadow">
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-6 py-3 rounded-md bg-white text-indigo-700 font-semibold hover:bg-indigo-50 shadow">
                        Login Now
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-6 py-3 rounded-md bg-indigo-700 text-white font-semibold hover:bg-indigo-800 border border-indigo-400">
                            Create Account
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="py-8 bg-slate-900 text-slate-400 text-sm">
        <div class="max-w-6xl mx-auto px-6 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center justify-center w-7 h-7 rounded bg-indigo-600 text-white font-bold text-xs">D</span>
                <span class="text-white font-medium">Bank Doc Gen</span>
            </div>
            <div>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</div>
        </div>
    </footer>

</body>
</html>
