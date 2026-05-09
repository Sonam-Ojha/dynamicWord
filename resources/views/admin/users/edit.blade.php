@extends('admin.layouts.app')

@section('title', 'Edit User')
@section('page_title', 'Edit User')

@section('content')
    <x-admin.card>
        <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data">
            @method('PUT')
            @include('admin.users._form', ['submitLabel' => 'Update User'])
        </form>
    </x-admin.card>
@endsection
