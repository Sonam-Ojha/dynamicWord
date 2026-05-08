@extends('admin.layouts.app')

@section('title', 'Create Document')
@section('page_title', 'Create Document')

@section('content')
    <x-admin.card>
        <form method="POST" action="{{ route('admin.documents.store') }}" enctype="multipart/form-data">
            @include('admin.documents._form', ['submitLabel' => 'Create Document'])
        </form>
    </x-admin.card>
@endsection
