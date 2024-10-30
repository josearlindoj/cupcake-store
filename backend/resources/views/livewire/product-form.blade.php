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

    <!-- Formulário de Cadastro -->
    <form wire:submit.prevent="save" class="space-y-6">
        <!-- Nome do Produto -->
        <div>
            <label class="block text-sm font-medium leading-6 text-gray-900">Product Name</label>
            <input type="text" wire:model="name"
                   class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            @error('name') <span class="text-red-600">{{ $message }}</span> @enderror
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
