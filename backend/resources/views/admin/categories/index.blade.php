@extends('layouts.admin')

@section('content')
    <div class="container mx-auto">
        <div class="grid grid-cols-2 w-3/12">
            <h2 class="mt-4 text-3xl font-extrabold leading-none tracking-tight text-gray-900 md:text-4xl">
                Categories</h2>

            <a href="{{ route('categories.create') }}" type="button"
               class="text-white bg-blue-700 my-5 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Create New Product
                <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                     fill="none" viewBox="0 0 14 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M1 5h12m0 0L9 1m4 4L9 9"/>
                </svg>
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-200 text-green-800 p-2 mt-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="relative overflow-x-auto shadow-md sm:rounded">
            <table class="w-full text-sm text-left text-gray-700">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                <tr class="bg-gray-100">
                    <th scope="col" class="px-6 py-3">Name</th>
                    <th scope="col" class="px-6 py-3">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($categories as $category)
                    <tr class="odd:bg-white even:bg-gray-50 border-b border-gray-200">
                        <td scope="row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $category->name }}</td>
                        <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            <a href="{{ route('categories.edit', $category->id) }}"
                               class="font-medium text-blue-600 hover:underline">Edit</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
