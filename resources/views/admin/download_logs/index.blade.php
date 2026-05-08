@extends('admin.layouts.app')

@section('title', 'Download Logs')
@section('page_title', 'Download Logs')

@section('content')
    <div class="flex flex-wrap gap-3 mb-4 bg-white rounded-lg border border-slate-200 p-4">
        <div class="flex-1 min-w-[140px] flex items-center gap-3 px-3 py-2 rounded-md border border-slate-200">
            <div class="w-10 h-10 rounded-md bg-slate-100 text-slate-700 flex items-center justify-center font-bold">{{ $stats['total'] }}</div>
            <div>
                <div class="text-xs text-slate-500 uppercase">Total</div>
                <div class="text-sm font-semibold text-slate-900">All Downloads</div>
            </div>
        </div>
        <div class="flex-1 min-w-[140px] flex items-center gap-3 px-3 py-2 rounded-md border border-indigo-200 bg-indigo-50">
            <div class="w-10 h-10 rounded-md bg-indigo-600 text-white flex items-center justify-center font-bold">{{ $stats['today'] }}</div>
            <div>
                <div class="text-xs text-indigo-700 uppercase">Today</div>
                <div class="text-sm font-semibold text-indigo-900">Last 24h</div>
            </div>
        </div>
        <div class="flex-1 min-w-[140px] flex items-center gap-3 px-3 py-2 rounded-md border border-emerald-200 bg-emerald-50">
            <div class="w-10 h-10 rounded-md bg-emerald-600 text-white flex items-center justify-center font-bold">{{ $stats['week'] }}</div>
            <div>
                <div class="text-xs text-emerald-700 uppercase">Week</div>
                <div class="text-sm font-semibold text-emerald-900">Last 7 days</div>
            </div>
        </div>
        <div class="flex-1 min-w-[140px] flex items-center gap-3 px-3 py-2 rounded-md border border-amber-200 bg-amber-50">
            <div class="w-10 h-10 rounded-md bg-amber-600 text-white flex items-center justify-center font-bold">{{ $stats['unique_users'] }}</div>
            <div>
                <div class="text-xs text-amber-700 uppercase">Users</div>
                <div class="text-sm font-semibold text-amber-900">Unique</div>
            </div>
        </div>
    </div>

    <x-admin.card>
        <form method="GET" action="{{ route('admin.download-logs.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-3 mb-6">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Document #..."
                   class="lg:col-span-2 rounded-md border-slate-300 text-sm">
            <select name="user_id" class="rounded-md border-slate-300 text-sm">
                <option value="">All Users</option>
                @foreach ($users as $u)
                    <option value="{{ $u->id }}" @selected(request('user_id') == $u->id)>{{ $u->name }}</option>
                @endforeach
            </select>
            <select name="template_id" class="rounded-md border-slate-300 text-sm">
                <option value="">All Templates</option>
                @foreach ($templates as $t)
                    <option value="{{ $t->id }}" @selected(request('template_id') == $t->id)>{{ $t->template_name }}</option>
                @endforeach
            </select>
            <select name="bank_id" class="rounded-md border-slate-300 text-sm">
                <option value="">All Banks</option>
                @foreach ($banks as $b)
                    <option value="{{ $b->id }}" @selected(request('bank_id') == $b->id)>{{ $b->bank_name }}</option>
                @endforeach
            </select>
            <input type="date" name="from" value="{{ request('from') }}" class="rounded-md border-slate-300 text-sm" placeholder="From">
            <input type="date" name="to" value="{{ request('to') }}" class="rounded-md border-slate-300 text-sm" placeholder="To">
            <div class="flex gap-2 lg:col-span-6">
                <button type="submit" class="px-4 py-2 rounded-md bg-slate-800 text-white text-sm hover:bg-slate-900">Filter</button>
                <a href="{{ route('admin.download-logs.index') }}" class="px-4 py-2 rounded-md bg-white border border-slate-300 text-sm">Reset</a>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-slate-600 text-xs uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">When</th>
                        <th class="px-4 py-3 text-left">Who</th>
                        <th class="px-4 py-3 text-left">Document #</th>
                        <th class="px-4 py-3 text-left">Template</th>
                        <th class="px-4 py-3 text-left">Bank</th>
                        <th class="px-4 py-3 text-left">IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($logs as $log)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3">
                                <div class="text-slate-900">{{ $log->created_at->format('Y-m-d H:i:s') }}</div>
                                <div class="text-xs text-slate-500">{{ $log->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-4 py-3 font-medium">{{ $log->user?->name ?? '—' }}</td>
                            <td class="px-4 py-3 font-mono text-xs">{{ $log->document?->document_number ?? '— deleted —' }}</td>
                            <td class="px-4 py-3">{{ $log->template?->template_name ?? '—' }}</td>
                            <td class="px-4 py-3">{{ $log->bank?->bank_name ?? '—' }}</td>
                            <td class="px-4 py-3 text-slate-500 text-xs">{{ $log->ip_address }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-8 text-center text-slate-500">No download logs match your filters.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $logs->links() }}</div>
    </x-admin.card>
@endsection
