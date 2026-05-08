@extends('admin.layouts.app')

@section('title', 'Edit Role')
@section('page_title', 'Edit Role')

@section('content')
    <x-admin.card>
        <form method="POST" action="{{ route('admin.roles.update', $role) }}">
            @method('PUT')
            @include('admin.roles._form', ['submitLabel' => 'Update Role'])
        </form>
    </x-admin.card>
@endsection
