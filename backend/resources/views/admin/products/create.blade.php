@extends('layouts.admin')

@section('content')
    <div class="container mx-auto">
        <a href="{{ route('products.index') }}" class="inline-flex mt-4 items-center text-sm font-medium text-blue-600 hover:text-blue-800 focus:outline-none">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Product List
        </a>

        <div class="grid grid-cols-2 w-12/12">
            <h2 class="mt-4 text-3xl font-extrabold leading-none tracking-tight text-gray-900 md:text-4xl">Create
                Product</h2>
        </div>

        <div class="relative overflow-x-auto">
            <form class="space-y-6" method="POST" action="{{ route('products.store') }}">
                @csrf

                <!-- Product Name -->
                <div>
                    <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Product Name</label>
                    <div class="mt-2">
                        <input id="name" name="name" type="text" required
                               class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <!-- Category Selection -->
                <div>
                    <label for="category_id" class="block text-sm font-medium leading-6 text-gray-900">Category</label>
                    <div class="mt-2">
                        <select id="category_id" name="category_id" required
                                class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm
                       ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2
                       focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Product Description -->
                <div>
                    <label for="description"
                           class="block text-sm font-medium leading-6 text-gray-900">Description</label>
                    <div class="mt-2">
                        <textarea id="description" name="description" rows="4" required
                                  class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                    </div>
                </div>

                <!-- Product Price -->
                <div>
                    <label for="price" class="block text-sm font-medium leading-6 text-gray-900">Price</label>
                    <div class="mt-2">
                        <input id="price" name="price" type="number" step="0.01" required
                               class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <!-- Product Stock -->
                <div>
                    <label for="stock" class="block text-sm font-medium leading-6 text-gray-900">Stock</label>
                    <div class="mt-2">
                        <input id="stock" name="stock" type="number" required
                               class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button
                        class="flex w-1/2 justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                        type="submit">Create Product
                    </button>
                </div>

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="mt-4">
                        <strong class="text-red-600">{{ $errors->first() }}</strong>
                    </div>
                @endif
            </form>
        </div>
    </div>
@endsection
