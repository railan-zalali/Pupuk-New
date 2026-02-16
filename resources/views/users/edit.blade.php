<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
            {{ __('Edit Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card p-6 sm:p-8">
                <header class="mb-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Edit Informasi Pengguna') }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Perbarui detail pengguna dan peran.') }}
                    </p>
                </header>

                <form action="{{ route('users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div class="relative">
                            <input type="text" id="name" name="name"
                                class="peer input-primary w-full pb-2 pt-6 px-4 rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-emerald-500 focus:ring-emerald-500"
                                placeholder=" "
                                value="{{ old('name', $user->name) }}"
                                required />
                            <label for="name"
                                class="absolute left-4 top-4 text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-3 scale-75 origin-[0] peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 pointer-events-none">
                                {{ __('Nama Lengkap') }}
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <!-- Email -->
                        <div class="relative">
                            <input type="email" id="email" name="email"
                                class="peer input-primary w-full pb-2 pt-6 px-4 rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-emerald-500 focus:ring-emerald-500"
                                placeholder=" "
                                value="{{ old('email', $user->email) }}"
                                required />
                            <label for="email"
                                class="absolute left-4 top-4 text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-3 scale-75 origin-[0] peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 pointer-events-none">
                                {{ __('Email') }}
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>

                        <!-- Password -->
                        <div class="relative">
                            <input type="password" id="password" name="password"
                                class="peer input-primary w-full pb-2 pt-6 px-4 rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-emerald-500 focus:ring-emerald-500"
                                placeholder=" " />
                            <label for="password"
                                class="absolute left-4 top-4 text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-3 scale-75 origin-[0] peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 pointer-events-none">
                                {{ __('Kata Sandi Baru (Opsional)') }}
                            </label>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 ml-1">Kosongkan jika tidak ingin mengubah kata sandi</p>
                            <x-input-error class="mt-2" :messages="$errors->get('password')" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="relative">
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="peer input-primary w-full pb-2 pt-6 px-4 rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-emerald-500 focus:ring-emerald-500"
                                placeholder=" " />
                            <label for="password_confirmation"
                                class="absolute left-4 top-4 text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-3 scale-75 origin-[0] peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 pointer-events-none">
                                {{ __('Konfirmasi Kata Sandi Baru') }}
                            </label>
                        </div>

                        <!-- Roles -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Peran (Role)') }}</label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                @foreach ($roles as $role)
                                <label class="relative flex items-start p-4 border rounded-xl cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ in_array($role->id, old('roles', $user->roles->pluck('id')->toArray())) ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20' : 'border-gray-200 dark:border-gray-700' }}">
                                    <div class="min-w-0 flex-1 text-sm">
                                        <div class="font-medium text-gray-700 dark:text-gray-200 select-none">{{ $role->name }}</div>
                                    </div>
                                    <div class="ml-3 flex items-center h-5">
                                        <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                            class="focus:ring-emerald-500 h-4 w-4 text-emerald-600 border-gray-300 rounded"
                                            {{ in_array($role->id, old('roles', $user->roles->pluck('id')->toArray())) ? 'checked' : '' }}>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('roles')" />
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" onclick="window.history.back()" class="btn-ghost">
                            {{ __('Batal') }}
                        </button>
                        <button type="submit" class="btn-primary">
                            {{ __('Perbarui Pengguna') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>