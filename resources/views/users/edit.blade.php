<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Edit User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <x-input-label for="name" :value="__('Name')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                    :value="old('name', $user->name)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                                    :value="old('email', $user->email)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('email')" />
                            </div>

                            <div>
                                <x-input-label for="password" :value="__('New Password')" />
                                <x-text-input id="password" name="password" type="password"
                                    class="mt-1 block w-full" />
                                <p class="mt-1 text-sm text-gray-500">Leave blank to keep current password</p>
                                <x-input-error class="mt-2" :messages="$errors->get('password')" />
                            </div>

                            <div>
                                <x-input-label for="password_confirmation" :value="__('Confirm New Password')" />
                                <x-text-input id="password_confirmation" name="password_confirmation" type="password"
                                    class="mt-1 block w-full" />
                            </div>

                            <div>
                                <x-input-label for="roles" :value="__('Roles')" />
                                <div class="mt-2 space-y-2">
                                    @foreach ($roles as $role)
                                        <label class="inline-flex items-center">
                                            <input type="checkbox"
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                name="roles[]" value="{{ $role->id }}"
                                                {{ in_array($role->id, old('roles', $user->roles->pluck('id')->toArray())) ? 'checked' : '' }}>
                                            <span class="ml-2">{{ $role->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('roles')" />
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <x-secondary-button type="button" onclick="window.history.back()" class="mr-3">
                                {{ __('Cancel') }}
                            </x-secondary-button>
                            <x-primary-button>
                                {{ __('Update User') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
