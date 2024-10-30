<div class="container mx-auto">
    <h2 class="mt-4 text-3xl font-extrabold leading-none tracking-tight text-gray-900 md:text-4xl">
        {{ $attributeId ? 'Edit Attribute' : 'Create Attribute' }}
    </h2>

    <form wire:submit.prevent="save" class="space-y-6 mt-4">
        <div>
            <label class="block text-sm font-medium leading-6 text-gray-900">Attribute Name</label>
            <input type="text" wire:model="name"
                   class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            @error('name') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <h3 class="text-lg font-semibold text-gray-900">Attribute Options</h3>
        <div>
            @foreach($options as $index => $option)
                <div class="flex items-center mb-2">
                    <input type="text" wire:model="options.{{ $index }}"
                           class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mr-2">
                    <button type="button" wire:click="removeOption({{ $index }})" class="text-red-600">Remove</button>
                </div>
                @error('options.' . $index) <span class="text-red-600">{{ $message }}</span> @enderror
            @endforeach
        </div>

        <button type="button" wire:click="addOption" class="mt-2 bg-blue-500 text-white px-3 py-1.5 rounded-md">
            Add Option
        </button>

        <button type="submit" class="bg-indigo-600 text-white px-3 py-1.5 rounded-md mt-4">
            {{ $attributeId ? 'Update Attribute' : 'Save Attribute' }}
        </button>
    </form>
</div>
