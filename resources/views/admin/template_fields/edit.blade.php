@extends('admin.layouts.app')

@section('title', 'Edit Field')
@section('page_title', 'Edit Field — '.$template->template_name)

@section('content')
    <x-admin.card>
        <form method="POST" action="{{ route('admin.fields.update', $field) }}">
            @method('PUT')
            @include('admin.template_fields._form', ['submitLabel' => 'Update Field'])
        </form>
    </x-admin.card>
@endsection
