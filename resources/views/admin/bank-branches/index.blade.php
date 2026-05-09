@extends('admin.layouts.app')

@section('title', 'Bank Branches')
@section('page_title', 'Bank Branches')

@section('page_actions')
    <x-admin.button :href="route('admin.bank-branches.create')">+ New Branch</x-admin.button>
@endsection

@section('content')
    <x-admin.card>
        <form method="GET" action="{{ route('admin.bank-branches.index') }}" class="flex flex-col sm:flex-row sm:items-end gap-3 mb-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-slate-700 mb-1">Search</label>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Branch name, code, IFSC, city..."
                       class="w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            </div>
            <div class="w-full sm:w-64">
                <label class="block text-sm font-medium text-slate-700 mb-1">Bank</label>
                <select name="bank_id" class="w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    <option value="">All Banks</option>
                    @foreach ($banks as $bank)
                        <option value="{{ $bank->id }}" @selected(request('bank_id') == $bank->id)>{{ $bank->bank_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <x-admin.button type="submit" variant="primary">Filter</x-admin.button>
                <x-admin.button :href="route('admin.bank-branches.index')" variant="secondary">Reset</x-admin.button>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-slate-600 text-xs uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">Bank</th>
                        <th class="px-4 py-3 text-left">Branch Name</th>
                        <th class="px-4 py-3 text-left">Branch Code</th>
                        <th class="px-4 py-3 text-left">IFSC</th>
                        <th class="px-4 py-3 text-left">City</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($branches as $branch)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3">{{ $branch->bank->bank_name ?? '—' }}</td>
                            <td class="px-4 py-3 font-medium">{{ $branch->branch_name }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $branch->branch_code }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $branch->ifsc_code ?? '—' }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $branch->city ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <x-admin.status-toggle :action="route('admin.bank-branches.toggle-status', $branch)" :active="$branch->status" />
                            </td>
                            <td class="px-4 py-3 text-right space-x-3">
                                <a href="{{ route('admin.bank-branches.edit', $branch) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">Edit</a>
                                <x-admin.delete-form :action="route('admin.bank-branches.destroy', $branch)" />
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-4 py-8 text-center text-slate-500">No branches found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $branches->links() }}</div>
    </x-admin.card>
@endsection
