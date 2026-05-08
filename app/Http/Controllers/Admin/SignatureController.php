<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignatureRequest;
use App\Models\Signature;
use App\Models\User;
use App\Services\FileUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SignatureController extends Controller
{
    public function __construct(private FileUploadService $files)
    {
    }

    public function index(Request $request): View
    {
        $signatures = Signature::query()
            ->with('user')
            ->when($request->input('q'), fn ($q, $v) => $q->where('signature_name', 'like', "%{$v}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.signatures.index', compact('signatures'));
    }

    public function create(): View
    {
        $users = User::active()->get(['id', 'name']);
        return view('admin.signatures.create', compact('users'));
    }

    public function store(SignatureRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['status'] = $request->boolean('status');
        $data['signature_path'] = $this->files->upload($request->file('signature_path'), 'signatures');

        Signature::create($data);

        return redirect()->route('admin.signatures.index')->with('success', 'Signature created.');
    }

    public function edit(Signature $signature): View
    {
        $users = User::active()->get(['id', 'name']);
        return view('admin.signatures.edit', compact('signature', 'users'));
    }

    public function update(SignatureRequest $request, Signature $signature): RedirectResponse
    {
        $data = $request->validated();
        $data['status'] = $request->boolean('status');

        if ($request->hasFile('signature_path')) {
            $data['signature_path'] = $this->files->replace($signature->signature_path, $request->file('signature_path'), 'signatures');
        } else {
            unset($data['signature_path']);
        }

        $signature->update($data);

        return redirect()->route('admin.signatures.index')->with('success', 'Signature updated.');
    }

    public function destroy(Signature $signature): RedirectResponse
    {
        $this->files->delete($signature->signature_path);
        $signature->delete();

        return redirect()->route('admin.signatures.index')->with('success', 'Signature deleted.');
    }
}
