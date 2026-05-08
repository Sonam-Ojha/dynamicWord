@extends('admin.layouts.app')

@section('title', 'Create Signature')
@section('page_title', 'Create Signature')

@section('content')
    <x-admin.card>
        <form method="POST" action="{{ route('admin.signatures.store') }}" enctype="multipart/form-data">
            @include('admin.signatures._form', ['submitLabel' => 'Create Signature'])
        </form>
    </x-admin.card>
@endsection
