<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BankBranchRequest;
use App\Models\Bank;
use App\Models\BankBranch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BankBranchController extends Controller
{
    public function index(Request $request): View
    {
        $branches = BankBranch::query()
            ->with('bank')
            ->when($request->input('bank_id'), fn ($q, $id) => $q->where('bank_id', $id))
            ->search($request->input('q'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $banks = Bank::orderBy('bank_name')->get();

        return view('admin.bank-branches.index', compact('branches', 'banks'));
    }

    public function create(Request $request): View
    {
        $banks = Bank::active()->orderBy('bank_name')->get();
        $selectedBankId = $request->input('bank_id');

        return view('admin.bank-branches.create', compact('banks', 'selectedBankId'));
    }

    public function store(BankBranchRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['status'] = $request->boolean('status');

        BankBranch::create($data);

        return redirect()->route('admin.bank-branches.index')->with('success', 'Branch created successfully.');
    }

    public function edit(BankBranch $bankBranch): View
    {
        $banks = Bank::active()->orderBy('bank_name')->get();

        return view('admin.bank-branches.edit', ['branch' => $bankBranch, 'banks' => $banks]);
    }

    public function update(BankBranchRequest $request, BankBranch $bankBranch): RedirectResponse
    {
        $data = $request->validated();
        $data['status'] = $request->boolean('status');

        $bankBranch->update($data);

        return redirect()->route('admin.bank-branches.index')->with('success', 'Branch updated successfully.');
    }

    public function destroy(BankBranch $bankBranch): RedirectResponse
    {
        $bankBranch->delete();

        return redirect()->route('admin.bank-branches.index')->with('success', 'Branch deleted successfully.');
    }

    public function toggleStatus(BankBranch $bankBranch): RedirectResponse
    {
        $bankBranch->update(['status' => ! $bankBranch->status]);

        return back()->with('success', 'Status updated.');
    }
}
