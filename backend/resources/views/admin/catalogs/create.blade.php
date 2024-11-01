@extends('layouts.admin')

@section('content')
    <div class="container mx-auto">
        @livewire('catalog-form', ['attributeId' => $id ?? null])
    </div>
@endsection
