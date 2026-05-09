@extends('frontend.layouts.app')

@section('title', 'Select Bank')

@section('steps')
    @include('frontend.partials.steps', ['current' => 1])
@endsection

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-900">Select a Bank</h1>
        <p class="text-slate-500">Choose the bank for which you want to generate a document.</p>
    </div>

    @if ($banks->isEmpty())
        <div class="bg-white border border-slate-200 rounded-lg p-10 text-center text-slate-500">
            No active banks available. Contact your administrator.
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($banks as $bank)
                <a href="{{ route('generate.templates', $bank) }}"
                   class="group bg-white border border-slate-200 rounded-lg p-5 hover:border-indigo-500 hover:shadow-md transition">
                    <div class="flex items-center gap-4">
                        @if ($bank->logo)
                            <img src="{{ asset('storage/'.$bank->logo) }}" class="w-14 h-14 rounded object-cover" alt="">
                        @else
                            <div class="w-14 h-14 rounded bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xl">
                                {{ strtoupper(substr($bank->bank_name, 0, 2)) }}
                            </div>
                        @endif
                        <div class="min-w-0">
                            <div class="font-semibold text-slate-900 group-hover:text-indigo-600 truncate">{{ $bank->bank_name }}</div>
                            <div class="text-xs text-slate-500">{{ $bank->bank_code }}</div>
                        </div>
                    </div>
                    @if ($bank->description)
                        <p class="mt-3 text-sm text-slate-600 line-clamp-2">{{ $bank->description }}</p>
                    @endif
                    <div class="mt-4 text-xs text-indigo-600 font-medium">Choose →</div>
                </a>
            @endforeach
        </div>
    @endif
@endsection
