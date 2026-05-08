@extends('admin.layouts.app')

@section('title', 'Create Role')
@section('page_title', 'Create Role')

@section('content')
    <x-admin.card>
        <form method="POST" action="{{ route('admin.roles.store') }}">
            @include('admin.roles._form', ['submitLabel' => 'Create Role', 'rolePermissions' => []])
        </form>
    </x-admin.card>
@endsection
