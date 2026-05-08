@extends('admin.layouts.app')

@section('title', 'Edit Document')
@section('page_title', 'Edit Document')

@section('content')
    <x-admin.card>
        <form method="POST" action="{{ route('admin.documents.update', $document) }}" enctype="multipart/form-data">
            @method('PUT')
            @include('admin.documents._form', ['submitLabel' => 'Update Document'])
        </form>
    </x-admin.card>
@endsection
