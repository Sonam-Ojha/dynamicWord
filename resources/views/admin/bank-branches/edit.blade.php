@extends('admin.layouts.app')

@section('title', 'Edit Branch')
@section('page_title', 'Edit Branch')

@section('content')
    <x-admin.card>
        <form method="POST" action="{{ route('admin.bank-branches.update', $branch) }}">
            @method('PUT')
            @include('admin.bank-branches._form', ['submitLabel' => 'Update Branch'])
        </form>
    </x-admin.card>
@endsection
