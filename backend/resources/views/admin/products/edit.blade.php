@extends('layouts.admin')

@section('content')
    <div class="container mx-auto">
        <!-- Livewire Component for Editing Product -->
        @livewire('product-form', ['productId' => $product->id])
    </div>
@endsection
