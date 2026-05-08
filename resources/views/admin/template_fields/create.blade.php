@extends('admin.layouts.app')

@section('title', 'Create Field')
@section('page_title', 'Create Field — '.$template->template_name)

@section('content')
    <x-admin.card>
        <form method="POST" action="{{ route('admin.templates.fields.store', $template) }}">
            @include('admin.template_fields._form', ['submitLabel' => 'Create Field'])
        </form>
    </x-admin.card>
@endsection
