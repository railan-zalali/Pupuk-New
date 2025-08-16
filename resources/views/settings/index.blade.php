<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengaturan Toko') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('settings.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900">Informasi Toko</h3>
                            <p class="mt-1 text-sm text-gray-600">Informasi ini akan ditampilkan pada nota dan dokumen lainnya.</p>
                        </div>

                        <!-- Logo Toko -->
                        <div class="mb-6">
                            <label for="logo" class="block text-sm font-medium text-gray-700">Logo Toko</label>
                            <div class="mt-1 flex items-center">
                                @if($settings->logo_path)
                                    <div class="mb-3">
                                        <img src="{{ asset('storage/' . $settings->logo_path) }}" alt="Logo Toko" class="h-20 w-auto">
                                    </div>
                                @endif
                                <input type="file" name="logo" id="logo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            @error('logo')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nama Toko -->
                        <div class="mb-6">
                            <label for="store_name" class="block text-sm font-medium text-gray-700">Nama Toko</label>
                            <input type="text" name="store_name" id="store_name" value="{{ old('store_name', $settings->store_name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            @error('store_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Alamat Toko -->
                        <div class="mb-6">
                            <label for="store_address" class="block text-sm font-medium text-gray-700">Alamat Toko</label>
                            <textarea name="store_address" id="store_address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('store_address', $settings->store_address) }}</textarea>
                            @error('store_address')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nomor Telepon Toko -->
                        <div class="mb-6">
                            <label for="store_phone" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                            <input type="text" name="store_phone" id="store_phone" value="{{ old('store_phone', $settings->store_phone) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('store_phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email Toko -->
                        <div class="mb-6">
                            <label for="store_email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="store_email" id="store_email" value="{{ old('store_email', $settings->store_email) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('store_email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Simpan Pengaturan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>