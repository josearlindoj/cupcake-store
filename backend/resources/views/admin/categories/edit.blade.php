@extends('layouts.admin')

@section('content')
    <div class="container mx-auto">
        <a href="{{ route('categories.index') }}"
           class="inline-flex mt-4 items-center text-sm font-medium text-blue-600 hover:text-blue-800 focus:outline-none">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                 xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Category List
        </a>

        <div class="grid grid-cols-2 w-12/12">
            <h2 class="mt-4 text-3xl font-extrabold leading-none tracking-tight text-gray-900 md:text-4xl">Edit
                Category</h2>
        </div>

        <div class="relative overflow-x-auto">
            <form method="POST" action="{{ route('categories.update', $category->id) }}" class="space-y-6">
                @csrf
                @method('PUT')
                <div>
                    <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Category Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $category->name) }}" required
                           class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    @error('name')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mt-4">
                    <button type="submit"
                            class="flex w-1/2 justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Update Category
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
