@extends('admin.layouts.app')

@section('title', 'Edit Permission')
@section('page_title', 'Edit Permission')

@section('content')
    <x-admin.card>
        <form method="POST" action="{{ route('admin.permissions.update', $permission) }}">
            @method('PUT')
            @include('admin.permissions._form', ['submitLabel' => 'Update Permission'])
        </form>
    </x-admin.card>
@endsection
