@extends('admin.layouts.app')

@section('title', 'Edit Category')
@section('page_title', 'Edit Category')

@section('content')
    <x-admin.card>
        <form method="POST" action="{{ route('admin.categories.update', $category) }}">
            @method('PUT')
            @include('admin.categories._form', ['submitLabel' => 'Update Category'])
        </form>
    </x-admin.card>
@endsection
