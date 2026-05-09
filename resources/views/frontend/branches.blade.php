@extends('frontend.layouts.app')

@section('title', 'Select Branch')

@section('steps')
    @include('frontend.partials.steps', ['current' => 2])
@endsection

@section('content')
    <div class="mb-6 flex items-start justify-between gap-3">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Select a Branch</h1>
            <p class="text-slate-500">For <span class="font-medium text-slate-700">{{ $bank->bank_name }}</span> — pick the branch.</p>
        </div>
        <a href="{{ route('generate.banks') }}" class="text-sm text-slate-600 hover:text-indigo-600">← Change bank</a>
    </div>

    @if ($branches->isEmpty())
        <div class="bg-white border border-slate-200 rounded-lg p-10 text-center text-slate-500">
            Is bank ki koi active branch nahi hai. Admin se contact karein.
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($branches as $branch)
                <a href="{{ route('generate.templates', ['bank' => $bank, 'branch' => $branch]) }}"
                   class="group bg-white border border-slate-200 rounded-lg p-5 hover:border-indigo-500 hover:shadow-md transition">
                    <div class="font-semibold text-slate-900 group-hover:text-indigo-600">{{ $branch->branch_name }}</div>
                    <div class="text-xs text-slate-500 mt-1">
                        @if ($branch->branch_code) Code: {{ $branch->branch_code }} @endif
                        @if ($branch->ifsc_code) · IFSC: {{ $branch->ifsc_code }} @endif
                    </div>
                    @if ($branch->city || $branch->state)
                        <div class="text-sm text-slate-600 mt-2">
                            {{ collect([$branch->city, $branch->state])->filter()->join(', ') }}
                        </div>
                    @endif
                    <div class="mt-4 text-xs text-indigo-600 font-medium">Choose →</div>
                </a>
            @endforeach
        </div>
    @endif
@endsection
