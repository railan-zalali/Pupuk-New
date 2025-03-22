<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Edit Supplier') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('suppliers.update', $supplier) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <x-input-label for="name" value="Supplier Name" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                    :value="old('name', $supplier->name)" required />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="phone" value="Phone Number" />
                                <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full"
                                    :value="old('phone', $supplier->phone)" required />
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="address" value="Address" />
                                <textarea id="address" name="address"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required>{{ old('address', $supplier->address) }}</textarea>
                                <x-input-error :messages="$errors->get('address')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="description" value="Description" />
                                <textarea id="description" name="description"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $supplier->description) }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <x-secondary-button type="button" onclick="window.history.back()" class="mr-3">
                                Cancel
                            </x-secondary-button>
                            <x-primary-button>
                                Update Supplier
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
