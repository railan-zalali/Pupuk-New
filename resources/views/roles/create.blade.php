<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Create Role') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('roles.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <x-input-label for="name" :value="__('Role Name')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                    :value="old('name')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-input-label for="permissions" :value="__('Permissions')" />
                                <div class="mt-2 grid grid-cols-1 md:grid-cols-3 gap-4">
                                    @foreach ($permissions as $permission)
                                        <label class="inline-flex items-center">
                                            <input type="checkbox"
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                name="permissions[]" value="{{ $permission->id }}"
                                                {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                            <span class="ml-2">{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('permissions')" />
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <x-secondary-button type="button" onclick="window.history.back()" class="mr-3">
                                {{ __('Cancel') }}
                            </x-secondary-button>
                            <x-primary-button>
                                {{ __('Create Role') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
