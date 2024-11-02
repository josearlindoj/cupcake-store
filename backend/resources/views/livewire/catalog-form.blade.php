<div class="container mx-auto">
    <h2 class="mt-4 text-3xl font-extrabold text-gray-900">
        {{ $catalogId ? 'Edit Catalog' : 'Create Catalog' }}
    </h2>

    <form wire:submit.prevent="save" class="space-y-6 mt-4">
        <div>
            <label class="block text-sm font-medium text-gray-900">Catalog Name</label>
            <input type="text" wire:model="name"
                   class="block w-full rounded-md border p-1.5 text-gray-900 shadow-sm focus:ring-indigo-600">
            @error('name') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-900">Description</label>
            <textarea wire:model="description"
                      class="block w-full rounded-md border p-1.5 text-gray-900 shadow-sm focus:ring-indigo-600"></textarea>
            @error('description') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-900">Products</label>
            <div class="space-y-2">
                @foreach($products as $product)
                    <label class="inline-flex items-center">
                        <input type="checkbox" wire:model="selectedProducts" value="{{ $product->id }}"
                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                        <span class="ml-2 text-gray-900">{{ $product->name }}</span>
                    </label>
                @endforeach
            </div>
            @error('selectedProducts') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="bg-indigo-600 text-white px-3 py-1.5 rounded-md mt-4">
            {{ $catalogId ? 'Update Catalog' : 'Save Catalog' }}
        </button>
    </form>
</div>
