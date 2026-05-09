@extends('admin.layouts.app')

@section('title', 'Create Branch')
@section('page_title', 'Create Branch')

@section('content')
    <x-admin.card>
        <form method="POST" action="{{ route('admin.bank-branches.store') }}">
            @include('admin.bank-branches._form', ['submitLabel' => 'Create Branch'])
        </form>
    </x-admin.card>
@endsection
