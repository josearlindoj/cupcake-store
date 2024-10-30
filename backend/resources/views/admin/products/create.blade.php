@extends('layouts.admin')

@section('content')
    <div class="container mx-auto">
        <!-- Livewire Component for Creating Product -->
        @livewire('product-form', ['productId' => null])
    </div>
@endsection
