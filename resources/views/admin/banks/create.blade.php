@extends('admin.layouts.app')

@section('title', 'Create Bank')
@section('page_title', 'Create Bank')

@section('content')
    <x-admin.card>
        <form method="POST" action="{{ route('admin.banks.store') }}" enctype="multipart/form-data">
            @include('admin.banks._form', ['submitLabel' => 'Create Bank'])
        </form>
    </x-admin.card>
@endsection
