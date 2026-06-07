@extends('admin.layouts.app')

@section('title', 'Draft Reminders')
@section('page_title', 'Draft Reminders')

@section('content')

    {{-- Stats + Send All Button --}}
    <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center text-xl font-bold">
                {{ $users->count() }}
            </div>
            <div>
                <div class="text-sm font-semibold text-slate-800">Users with pending drafts</div>
                <div class="text-xs text-slate-500">These users should receive a reminder today</div>
            </div>
        </div>

        @if($users->isNotEmpty())
        <form method="POST" action="{{ route('admin.draft-reminders.send-all') }}"
              onsubmit="return confirm('Send reminder mail to {{ $users->count() }} users?')">
            @csrf
            <button type="submit"
                    class="flex items-center gap-2 px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Send to All
            </button>
        </form>
        @endif
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm">
            ✅ {{ session('success') }}
        </div>
    @endif
    @if(session('info'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-blue-50 border border-blue-200 text-blue-800 text-sm">
            ℹ️ {{ session('info') }}
        </div>
    @endif

    {{-- Auto schedule info --}}
    <div class="mb-5 flex items-start gap-3 px-4 py-3 rounded-lg bg-slate-50 border border-slate-200 text-sm text-slate-600">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-400 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span>
            This reminder is sent automatically <strong>every day at 10:00 AM IST</strong>.
            You can also manually send it to individual users or all users below.
        </span>
    </div>

    {{-- Users Table --}}
    <x-admin.card>
        @if($users->isEmpty())
            <div class="text-center py-16 text-slate-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-lg font-semibold">No pending drafts!</p>
                <p class="text-sm mt-1">All users have completed their documents.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-slate-600 text-xs uppercase">
                        <tr>
                            <th class="px-4 py-3 text-left">User</th>
                            <th class="px-4 py-3 text-left">Email</th>
                            <th class="px-4 py-3 text-center">Draft Count</th>
                            <th class="px-4 py-3 text-left">Templates (ID — Name — Code)</th>
                            <th class="px-4 py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($users as $user)
                        <tr class="hover:bg-slate-50 align-top">
                            <td class="px-4 py-3 font-semibold text-slate-800">{{ $user->name }}</td>
                            <td class="px-4 py-3 text-slate-500">{{ $user->email }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-block px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 text-xs font-bold">
                                    {{ $user->generatedDocuments->count() }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col gap-1.5">
                                    @foreach($user->generatedDocuments as $doc)
                                    <div class="flex flex-wrap items-center gap-1.5 text-xs">
                                        <span class="px-1.5 py-0.5 rounded bg-blue-100 text-blue-700 font-mono font-semibold">
                                            #{{ $doc->template_id }}
                                        </span>
                                        <span class="text-slate-700 font-medium">
                                            {{ $doc->template?->template_name ?? '—' }}
                                        </span>
                                        @if($doc->template?->template_code)
                                        <span class="px-1.5 py-0.5 rounded bg-slate-100 text-slate-500 font-mono">
                                            {{ $doc->template->template_code }}
                                        </span>
                                        @endif
                                        <span class="text-slate-400">·</span>
                                        <span class="text-slate-400">{{ $doc->bank?->bank_name ?? '—' }}</span>
                                        <span class="text-slate-400">·</span>
                                        <span class="text-slate-400">{{ $doc->created_at->format('d M Y') }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <form method="POST" action="{{ route('admin.draft-reminders.send-one', $user) }}"
                                      onsubmit="return confirm('Send reminder mail to {{ $user->name }}?')">
                                    @csrf
                                    <button type="submit"
                                            class="px-3 py-1.5 rounded-md bg-white border border-blue-300 text-blue-700 text-xs font-semibold hover:bg-blue-50">
                                        Send Mail
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-admin.card>

@endsection
