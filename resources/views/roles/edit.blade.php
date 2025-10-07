<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
            {{ __('Edit Peran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('roles.update', $role) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <x-input-label for="name" :value="__('Nama Peran')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                    :value="old('name', $role->name)" :disabled="$role->id === 1" required />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                @if ($role->id === 1)
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Nama peran Admin tidak dapat diubah</p>
                                @endif
                            </div>

                            <div>
                                <x-input-label for="permissions" :value="__('Izin')" />
                                <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                                    @foreach ($permissions as $permission)
                                        <label class="inline-flex items-center">
                                            <input type="checkbox"
                                                class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:focus:ring-indigo-500"
                                                name="permissions[]" value="{{ $permission->id }}"
                                                {{ in_array($permission->id, old('permissions', $role->permissions->pluck('id')->toArray())) ? 'checked' : '' }}
                                                {{ $role->id === 1 ? 'checked disabled' : '' }}>
                                            <span class="ml-2 text-gray-700 dark:text-gray-300">{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('permissions')" />
                                @if ($role->id === 1)
                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Peran Admin selalu memiliki semua izin</p>
                                @endif
                            </div>

                            @if ($role->id !== 1)
                                <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-400 p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-yellow-700 dark:text-yellow-300">
                                                Mengubah izin akan mempengaruhi semua pengguna dengan peran ini.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center gap-4 mt-6">
                            <x-primary-button>{{ __('Perbarui Peran') }}</x-primary-button>
                            <a href="{{ route('roles.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-500 focus:bg-gray-700 dark:focus:bg-gray-500 active:bg-gray-900 dark:active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Batal') }}
                            </a>
                        </div>
                    </form>

                    @if ($role->id !== 1)
                        <div class="mt-6 border-t dark:border-gray-700 pt-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Pengguna dengan peran ini</h3>
                            <div class="mt-4">
                                @if ($role->users->count() > 0)
                                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3">
                                        @foreach ($role->users as $user)
                                            <div
                                                class="flex items-center space-x-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 p-3">
                                                <div class="flex-shrink-0">
                                                    <span
                                                        class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-gray-500 dark:bg-gray-600">
                                                        <span class="text-lg font-medium leading-none text-white">
                                                            {{ substr($user->name, 0, 1) }}
                                                        </span>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</p>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada pengguna dengan peran ini.</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
