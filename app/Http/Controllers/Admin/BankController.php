<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BankRequest;
use App\Models\Bank;
use App\Services\FileUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BankController extends Controller
{
    public function __construct(private FileUploadService $files)
    {
    }

    public function index(Request $request): View
    {
        $banks = Bank::query()
            ->search($request->input('q'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.banks.index', compact('banks'));
    }

    public function create(): View
    {
        return view('admin.banks.create');
    }

    public function store(BankRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['status'] = $request->boolean('status');

        if ($request->hasFile('logo')) {
            $data['logo'] = $this->files->upload($request->file('logo'), 'banks');
        }

        Bank::create($data);

        return redirect()->route('admin.banks.index')->with('success', 'Bank created successfully.');
    }

    public function edit(Bank $bank): View
    {
        return view('admin.banks.edit', compact('bank'));
    }

    public function update(BankRequest $request, Bank $bank): RedirectResponse
    {
        $data = $request->validated();
        $data['status'] = $request->boolean('status');

        if ($request->hasFile('logo')) {
            $data['logo'] = $this->files->replace($bank->logo, $request->file('logo'), 'banks');
        }

        $bank->update($data);

        return redirect()->route('admin.banks.index')->with('success', 'Bank updated successfully.');
    }

    public function destroy(Bank $bank): RedirectResponse
    {
        $this->files->delete($bank->logo);
        $bank->delete();

        return redirect()->route('admin.banks.index')->with('success', 'Bank deleted successfully.');
    }

    public function toggleStatus(Bank $bank): RedirectResponse
    {
        $bank->update(['status' => ! $bank->status]);

        return back()->with('success', 'Status updated.');
    }
}
