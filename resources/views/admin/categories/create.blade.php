@extends('admin.layouts.app')

@section('title', 'Create Category')
@section('page_title', 'Create Category')

@section('content')
    <x-admin.card>
        <form method="POST" action="{{ route('admin.categories.store') }}">
            @include('admin.categories._form', ['submitLabel' => 'Create Category'])
        </form>
    </x-admin.card>
@endsection
