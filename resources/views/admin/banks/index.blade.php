@extends('admin.layouts.app')

@section('title', 'Banks')
@section('page_title', 'Banks')

@section('page_actions')
    <x-admin.button :href="route('admin.banks.create')">+ New Bank</x-admin.button>
@endsection

@section('content')
    <x-admin.card>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
            <x-admin.search-bar :action="route('admin.banks.index')" placeholder="Search banks..." />
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-slate-600 text-xs uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">Logo</th>
                        <th class="px-4 py-3 text-left">Name</th>
                        <th class="px-4 py-3 text-left">Code</th>
                        <th class="px-4 py-3 text-left">Branches</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($banks as $bank)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3">
                                @if ($bank->logo && \Illuminate\Support\Facades\Storage::disk('public')->exists($bank->logo))
                                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($bank->logo) }}" class="w-10 h-10 rounded object-cover" alt="">
                                @else
                                    <div class="w-10 h-10 rounded bg-slate-100 flex items-center justify-center text-slate-400 text-xs">N/A</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 font-medium">{{ $bank->bank_name }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $bank->bank_code }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.bank-branches.index', ['bank_id' => $bank->id]) }}"
                                   class="inline-flex items-center px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-xs hover:bg-indigo-100 hover:text-indigo-700">
                                    {{ $bank->branches_count ?? 0 }} branches
                                </a>
                            </td>
                            <td class="px-4 py-3">
                                <x-admin.status-toggle :action="route('admin.banks.toggle-status', $bank)" :active="$bank->status" />
                            </td>
                            <td class="px-4 py-3 text-right space-x-3">
                                <a href="{{ route('admin.banks.edit', $bank) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">Edit</a>
                                <x-admin.delete-form :action="route('admin.banks.destroy', $bank)" />
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-8 text-center text-slate-500">No banks found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $banks->links() }}</div>
    </x-admin.card>
@endsection
