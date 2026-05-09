@extends('admin.layouts.app')

@section('title', 'Edit Template')
@section('page_title', 'Edit Template')

@section('page_actions')
    <div class="flex flex-wrap gap-2">
        <x-admin.button :href="route('admin.templates.fields.sync', $template)" variant="secondary">⚡ Auto-Detect Fields</x-admin.button>
        <x-admin.button :href="route('admin.templates.fields.index', $template)" variant="secondary">Manage Fields</x-admin.button>
    </div>
@endsection

@section('content')
    <x-admin.card>
        <form method="POST" action="{{ route('admin.templates.update', $template) }}" enctype="multipart/form-data">
            @method('PUT')
            @include('admin.templates._form', ['submitLabel' => 'Update Template'])
        </form>
    </x-admin.card>
@endsection
