@extends('layouts.admin')

@section('content')
    <div class="container mx-auto">
        @livewire('catalog-form', ['catalogId' => $id ?? null])
    </div>
@endsection
