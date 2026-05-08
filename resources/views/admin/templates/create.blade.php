@extends('admin.layouts.app')

@section('title', 'Create Template')
@section('page_title', 'Create Template')

@section('content')
    <x-admin.card>
        <form method="POST" action="{{ route('admin.templates.store') }}" enctype="multipart/form-data">
            @include('admin.templates._form', ['submitLabel' => 'Create Template'])
        </form>
    </x-admin.card>
@endsection
