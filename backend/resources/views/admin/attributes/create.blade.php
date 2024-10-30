@extends('layouts.admin')

@section('content')
    <div class="container mx-auto">
        @livewire('attribute-form', ['attributeId' => $id ?? null])
    </div>
@endsection
