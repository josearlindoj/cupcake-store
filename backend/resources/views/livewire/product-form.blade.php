<div class="container mx-auto">
    <!-- Link de Voltar -->
    <a href="{{ route('products.index') }}"
       class="inline-flex mt-4 items-center text-sm font-medium text-blue-600 hover:text-blue-800 focus:outline-none">
        Back to Product List
    </a>

    <!-- Título -->
    <div class="grid grid-cols-2 w-12/12">
        <h2 class="mt-4 text-3xl font-extrabold leading-none tracking-tight text-gray-900 md:text-4xl">
            {{ $isEdit ? 'Edit Product' : 'Create Product' }}
        </h2>
    </div>

    <form wire:submit.prevent="save" enctype="multipart/form-data" class="space-y-6">
        <div>
            <label class="block text-sm font-medium leading-6 text-gray-900">Product Name</label>
            <input type="text" wire:model="name"
                   class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            @error('name') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <div>
            <div class="grid grid-cols-2 gap-4">
                <!-- Product Images -->
                <div>
                    <label class="block text-sm font-medium leading-6 text-gray-900">Product Images (max 4)</label>
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Photo 1 -->
                        <div>
                            <input type="file" wire:model="photo1" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-white focus:outline-none">
                            @if ($photo1)
                                <div class="relative mt-2">
                                    <img src="{{ $photo1->temporaryUrl() }}" alt="Preview" class="w-full h-20 object-cover rounded-md">
                                    <button type="button" wire:click="removeImage(1)" class="absolute top-0 right-0 mt-1 mr-1 text-white bg-red-600 hover:bg-red-700 rounded-full p-1">
                                        &times;
                                    </button>
                                </div>
                            @endif
                        </div>
                        <!-- Photo 2 -->
                        <div>
                            <input type="file" wire:model="photo2" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-white focus:outline-none">
                            @if ($photo2)
                                <div class="relative mt-2">
                                    <img src="{{ $photo2->temporaryUrl() }}" alt="Preview" class="w-full h-20 object-cover rounded-md">
                                    <button type="button" wire:click="removeImage(2)" class="absolute top-0 right-0 mt-1 mr-1 text-white bg-red-600 hover:bg-red-700 rounded-full p-1">
                                        &times;
                                    </button>
                                </div>
                            @endif
                        </div>
                        <!-- Photo 3 -->
                        <div>
                            <input type="file" wire:model="photo3" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-white focus:outline-none">
                            @if ($photo3)
                                <div class="relative mt-2">
                                    <img src="{{ $photo3->temporaryUrl() }}" alt="Preview" class="w-full h-20 object-cover rounded-md">
                                    <button type="button" wire:click="removeImage(3)" class="absolute top-0 right-0 mt-1 mr-1 text-white bg-red-600 hover:bg-red-700 rounded-full p-1">
                                        &times;
                                    </button>
                                </div>
                            @endif
                        </div>
                        <!-- Photo 4 -->
                        <div>
                            <input type="file" wire:model="photo4" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-white focus:outline-none">
                            @if ($photo4)
                                <div class="relative mt-2">
                                    <img src="{{ $photo4->temporaryUrl() }}" alt="Preview" class="w-full h-20 object-cover rounded-md">
                                    <button type="button" wire:click="removeImage(4)" class="absolute top-0 right-0 mt-1 mr-1 text-white bg-red-600 hover:bg-red-700 rounded-full p-1">
                                        &times;
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Existing Images Previews -->
                    @if (!empty($photoPreviews))
                        <div class="mt-4 grid grid-cols-6 gap-4">
                            @foreach($photoPreviews as $index => $image)
                                <div class="relative">
                                    <img src="{{ $image['url'] }}" alt="Product Image" class="w-full h-20 object-cover rounded-md">
                                    <button type="button" wire:click="removeImage({{ $index }}, 'photoPreviews')"
                                            class="absolute top-0 right-0 mt-1 mr-1 text-white bg-red-600 hover:bg-red-700 rounded-full p-1">
                                        &times;
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <div>
            <label class="block text-sm font-medium leading-6 text-gray-900">Slug</label>
            <input type="text" wire:model="slug"
                   class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            @error('slug') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <h3 class="text-lg font-semibold text-gray-900">Variant</h3>
        @foreach($skus as $index => $sku)
            <div class="sku-row border rounded-md p-4 mb-2">
                <label class="block text-sm font-medium leading-6 text-gray-900">SKU Code</label>
                <input type="text" wire:model="skus.{{ $index }}.code"
                       class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                @error("skus.$index.code") <span class="text-red-600">{{ $message }}</span> @enderror

                <label class="block text-sm font-medium leading-6 text-gray-900 mt-2">Price</label>
                <input type="number" wire:model="skus.{{ $index }}.price" step="0.01"
                       class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                @error("skus.$index.price") <span class="text-red-600">{{ $message }}</span> @enderror

                <label class="block text-sm font-medium leading-6 text-gray-900 mt-2">Stock</label>
                <input type="number" wire:model="skus.{{ $index }}.stock"
                       class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                @error("skus.$index.stock") <span class="text-red-600">{{ $message }}</span> @enderror

                @foreach ($productAttributes as $attribute)
                    <label class="block text-sm font-medium leading-6 text-gray-900 mt-2">{{ $attribute['name'] }}</label>
                    <select wire:model="skus.{{ $index }}.attributes.{{ $attribute['id'] }}"
                            class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        <option value="">Select {{ $attribute['name'] }}</option>
                        @foreach ($attribute['options'] as $option)
                            <option value="{{ $option['id'] }}">{{ $option['value'] }}</option>
                        @endforeach
                    </select>
                    @error("skus.$index.attributes.{$attribute['id']}") <span class="text-red-600">{{ $message }}</span> @enderror
                @endforeach

                <button type="button" wire:click="removeSku({{ $index }})" class="text-red-600 mt-2">Remove SKU</button>
            </div>
        @endforeach

        <button type="button" wire:click.prevent="addSku" class="mt-2 bg-blue-500 text-white px-3 py-1.5 rounded-md">
            Add SKU
        </button>

        <button type="submit" class="bg-indigo-600 text-white px-3 py-1.5 rounded-md mt-4">
            Save Product
        </button>
    </form>
</div>
