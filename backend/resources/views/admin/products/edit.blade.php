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
            <h2 class="mt-4 text-3xl font-extrabold leading-none tracking-tight text-gray-900 md:text-4xl">Edit Product</h2>
        </div>

        <div class="relative overflow-x-auto">
            <form id="product-form" class="space-y-6" method="POST" action="{{ route('products.update', $product->id) }}">
                @csrf
                @method('PUT') <!-- Use PUT for updates -->

                <!-- Product Name -->
                <div>
                    <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Product Name</label>
                    <div class="mt-2">
                        <input id="name" name="name" type="text" value="{{ old('name', $product->name) }}" required
                               class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <!-- Category Selection -->
{{--                <div>--}}
{{--                    <label for="category_id" class="block text-sm font-medium leading-6 text-gray-900">Category</label>--}}
{{--                    <div class="mt-2">--}}
{{--                        <select id="category_id" name="category_id" required--}}
{{--                                class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">--}}
{{--                            <option value="">Select a category</option>--}}
{{--                            @foreach($categories as $category)--}}
{{--                                <option value="{{ $category->id }}"--}}
{{--                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>--}}
{{--                                    {{ $category->name }}--}}
{{--                                </option>--}}
{{--                            @endforeach--}}
{{--                        </select>--}}
{{--                    </div>--}}
{{--                </div>--}}

                <!-- Product Description -->
                <div>
                    <label for="description" class="block text-sm font-medium leading-6 text-gray-900">Description</label>
                    <div class="mt-2">
                        <textarea id="description" name="description" rows="4" required
                                  class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>

                <!-- SKU Section -->
                <h3 class="text-lg font-semibold leading-6 text-gray-900">Product SKUs</h3>
                <div id="sku-section">
                    <!-- Existing SKUs (for editing) -->
                    @foreach ($product->skus as $index => $sku)
                        <div class="sku-row border rounded-md p-4 mb-2">
                            <input type="hidden" name="skus[{{ $sku->id }}][id]" value="{{ $sku->id }}">
                            <div class="mb-2">
                                <label class="block text-sm font-medium leading-6 text-gray-900">SKU Code</label>
                                <input type="text" name="skus[{{ $sku->id }}][sku_code]" value="{{ old("skus.{$sku->id}.sku_code", $sku->sku_code) }}"
                                       class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                            <div class="mb-2">
                                <label class="block text-sm font-medium leading-6 text-gray-900">Price</label>
                                <input type="number" name="skus[{{ $sku->id }}][price]" value="{{ old("skus.{$sku->id}.price", $sku->price) }}" step="0.01"
                                       class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                            <div class="mb-2">
                                <label class="block text-sm font-medium leading-6 text-gray-900">Stock</label>
                                <input type="number" name="skus[{{ $sku->id }}][stock]" value="{{ old("skus.{$sku->id}.stock", $sku->stock) }}"
                                       class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                            <button type="button" class="text-red-600 hover:text-red-800" onclick="deleteSku(this, {{ $sku->id }})">Delete</button>
                        </div>
                    @endforeach

                    <!-- New SKU Template (hidden) -->
                    <div class="sku-row new-sku-template border rounded-md p-4 mb-2 hidden">
                        <div class="mb-2">
                            <label class="block text-sm font-medium leading-6 text-gray-900">SKU Code</label>
                            <input type="text" name="new_skus[][sku_code]"
                                   class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                        <div class="mb-2">
                            <label class="block text-sm font-medium leading-6 text-gray-900">Price</label>
                            <input type="number" name="new_skus[][price]" step="0.01"
                                   class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                        <div class="mb-2">
                            <label class="block text-sm font-medium leading-6 text-gray-900">Stock</label>
                            <input type="number" name="new_skus[][stock]"
                                   class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                        <button type="button" class="text-red-600 hover:text-red-800" onclick="removeSkuRow(this)">Remove</button>
                    </div>
                </div>

                <button type="button" class="mt-2 rounded-md bg-blue-500 px-3 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-400 focus:ring-2 focus:ring-blue-600" onclick="addSkuRow()">Add SKU</button>

                <!-- Submit Button -->
                <div>
                    <button class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600" type="submit">Update Product</button>
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

    <script>
        let skuIndex = {{ $product->skus->count() }};

        function addSkuRow() {
            const template = document.querySelector('.new-sku-template');
            const clone = template.cloneNode(true);
            clone.style.display = 'block';
            clone.classList.remove('new-sku-template', 'hidden');

            clone.querySelector('input[name="new_skus[][sku_code]"]').name = `new_skus[${skuIndex}][sku_code]`;
            clone.querySelector('input[name="new_skus[][price]"]').name = `new_skus[${skuIndex}][price]`;
            clone.querySelector('input[name="new_skus[][stock]"]').name = `new_skus[${skuIndex}][stock]`;

            document.getElementById('sku-section').appendChild(clone);
            skuIndex++;
        }

        function removeSkuRow(element) {
            element.closest('.sku-row').remove();
        }

        function deleteSku(element, skuId) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `delete_skus[]`;
            input.value = skuId;
            document.querySelector('form').appendChild(input);
            element.closest('.sku-row').remove();
        }

        document.getElementById('product-form').addEventListener('submit', function(event) {
            // Find all SKU rows
            const skuRows = document.querySelectorAll('#sku-section .sku-row');

            skuRows.forEach(row => {
                const skuCode = row.querySelector('input[name*="[sku_code]"]')?.value;
                const price = row.querySelector('input[name*="[price]"]')?.value;
                const stock = row.querySelector('input[name*="[stock]"]')?.value;

                // If all SKU fields are empty, remove the row from the form
                if (!skuCode && !price && !stock) {
                    row.remove();
                }
            });
        });
    </script>
@endsection
