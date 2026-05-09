@extends('admin.layouts.app')

@section('title', 'Edit Bank')
@section('page_title', 'Edit Bank')

@section('content')
    <x-admin.card>
        <form method="POST" action="{{ route('admin.banks.update', $bank) }}" enctype="multipart/form-data">
            @method('PUT')
            @include('admin.banks._form', ['submitLabel' => 'Update Bank'])
        </form>
    </x-admin.card>
@endsection
