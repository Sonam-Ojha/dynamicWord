@extends('admin.layouts.app')

@section('title', 'Create User')
@section('page_title', 'Create User')

@section('content')
    <x-admin.card>
        <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
            @include('admin.users._form', ['submitLabel' => 'Create User', 'userRoles' => []])
        </form>
    </x-admin.card>
@endsection
