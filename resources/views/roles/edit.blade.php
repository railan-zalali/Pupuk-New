<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">
            {{ __('Edit Peran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card p-6 sm:p-8">
                <header class="mb-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Edit Informasi Peran') }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Perbarui nama peran dan hak akses.') }}
                    </p>
                </header>

                <form action="{{ route('roles.update', $role) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-6">
                        <!-- Role Name -->
                        <div class="relative">
                            <input type="text" id="name" name="name"
                                class="peer input-primary w-full pb-2 pt-6 px-4 rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-emerald-500 focus:ring-emerald-500 disabled:opacity-50 disabled:bg-gray-100 dark:disabled:bg-gray-800"
                                placeholder=" "
                                value="{{ old('name', $role->name) }}"
                                :disabled="$role->id === 1" required />
                            <label for="name"
                                class="absolute left-4 top-4 text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-3 scale-75 origin-[0] peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 pointer-events-none">
                                {{ __('Nama Peran') }}
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            @if ($role->id === 1)
                            <p class="mt-1 text-xs text-amber-600 dark:text-amber-400">
                                <i class="ti ti-lock"></i> Nama peran Admin tidak dapat diubah
                            </p>
                            @endif
                        </div>

                        <!-- Permissions -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">{{ __('Izin (Permissions)') }}</label>

                            @if ($role->id === 1)
                            <div class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="ti ti-info-circle text-blue-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-blue-700 dark:text-blue-300">
                                            Peran Admin memiliki akses penuh ke semua fitur sistem. Izin tidak dapat diubah.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if ($role->id !== 1)
                            <div class="bg-amber-50 dark:bg-amber-900/20 border-l-4 border-amber-400 p-4 mb-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="ti ti-alert-triangle text-amber-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-amber-700 dark:text-amber-300">
                                            Mengubah izin akan mempengaruhi semua pengguna dengan peran ini.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach ($permissions as $permission)
                                <label class="relative flex items-start p-4 border rounded-xl cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ in_array($permission->id, old('permissions', $role->permissions->pluck('id')->toArray())) ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20' : 'border-gray-200 dark:border-gray-700' }} {{ $role->id === 1 ? 'opacity-75 cursor-not-allowed bg-gray-50 dark:bg-gray-800' : '' }}">
                                    <div class="min-w-0 flex-1 text-sm">
                                        <div class="font-medium text-gray-700 dark:text-gray-200 select-none">{{ $permission->name }}</div>
                                    </div>
                                    <div class="ml-3 flex items-center h-5">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                            class="focus:ring-emerald-500 h-4 w-4 text-emerald-600 border-gray-300 rounded"
                                            {{ in_array($permission->id, old('permissions', $role->permissions->pluck('id')->toArray())) ? 'checked' : '' }}
                                            {{ $role->id === 1 ? 'checked disabled' : '' }}>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('permissions')" />
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end gap-3">
                        <button type="button" onclick="window.history.back()" class="btn-ghost">
                            {{ __('Batal') }}
                        </button>
                        <button type="submit" class="btn-primary">
                            {{ __('Perbarui Peran') }}
                        </button>
                    </div>
                </form>

                @if ($role->id !== 1)
                <div class="mt-8 border-t dark:border-gray-700 pt-8">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Pengguna dengan peran ini</h3>
                    @if ($role->users->count() > 0)
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3">
                        @foreach ($role->users as $user)
                        <div class="flex items-center space-x-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 p-4">
                            <div class="flex-shrink-0">
                                <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900 text-emerald-600 dark:text-emerald-300 font-bold text-sm">
                                    {{ substr($user->name, 0, 1) }}
                                </span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $user->email }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-dashed border-gray-300 dark:border-gray-700">
                        <p>Belum ada pengguna dengan peran ini.</p>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>