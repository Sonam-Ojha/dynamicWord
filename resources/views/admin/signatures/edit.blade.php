@extends('admin.layouts.app')

@section('title', 'Edit Signature')
@section('page_title', 'Edit Signature')

@section('content')
    <x-admin.card>
        <form method="POST" action="{{ route('admin.signatures.update', $signature) }}" enctype="multipart/form-data">
            @method('PUT')
            @include('admin.signatures._form', ['submitLabel' => 'Update Signature'])
        </form>
    </x-admin.card>
@endsection
