@extends('frontend.layouts.app')

@section('title', 'Select Template')

@section('steps')
    @include('frontend.partials.steps', ['current' => 3])
@endsection

@section('content')
    <div class="mb-6 flex items-start justify-between gap-3">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Select Template</h1>
            <p class="text-slate-500">
                For <span class="font-medium text-slate-700">{{ $bank->bank_name }}</span>
                @isset($branch) · Branch: <span class="font-medium text-slate-700">{{ $branch->branch_name }}</span> @endisset
                — pick a document template to fill.
            </p>
        </div>
        <a href="{{ route('generate.branches', $bank) }}" class="text-sm text-slate-600 hover:text-indigo-600">← Change branch</a>
    </div>

    @if ($templates->isEmpty())
        <div class="bg-white border border-slate-200 rounded-lg p-10 text-center text-slate-500">
            No templates available for this bank yet.
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($templates as $template)
                <a href="{{ route('generate.form', ['template' => $template, 'branch' => $branch->id]) }}"
                   class="group bg-white border border-slate-200 rounded-lg overflow-hidden hover:border-indigo-500 hover:shadow-md transition flex flex-col">
                    <div class="aspect-video bg-slate-100 flex items-center justify-center overflow-hidden">
                        @if ($template->template_preview)
                            <img src="{{ asset('storage/'.$template->template_preview) }}" class="w-full h-full object-cover" alt="">
                        @else
                            <span class="text-slate-400 text-sm">No preview</span>
                        @endif
                    </div>
                    <div class="p-4 flex-1 flex flex-col">
                        <div class="flex items-center justify-between gap-2">
                            <div class="font-semibold text-slate-900 group-hover:text-indigo-600 truncate">{{ $template->template_name }}</div>
                        </div>
                        <div class="text-xs text-slate-500 mt-1">{{ $template->template_code }}</div>
                        @if ($template->description)
                            <p class="text-sm text-slate-600 mt-2 line-clamp-2">{{ $template->description }}</p>
                        @endif
                        <div class="mt-auto pt-3 text-xs text-indigo-600 font-medium">Use this template →</div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
@endsection
