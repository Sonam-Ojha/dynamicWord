@extends('admin.layouts.app')

@section('title', 'Create Permission')
@section('page_title', 'Create Permission')

@section('content')
    <x-admin.card>
        <form method="POST" action="{{ route('admin.permissions.store') }}">
            @include('admin.permissions._form', ['submitLabel' => 'Create Permission', 'permissionRoles' => []])
        </form>
    </x-admin.card>
@endsection
