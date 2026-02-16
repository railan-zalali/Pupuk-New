<x-app-layout>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between animate-fade-in-up">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ __('Manajemen Role') }}</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola peran dan izin akses pengguna</p>
            </div>

            <a href="{{ route('roles.create') }}" class="btn-primary flex items-center justify-center gap-2">
                <i class="ti ti-plus"></i>
                <span>Buat Role Baru</span>
            </a>
        </div>

        @if (session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 flex items-center gap-3 animate-fade-in-up">
            <i class="ti ti-check-circle text-xl"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
        @endif

        <div class="card overflow-hidden animate-fade-in-up delay-100">
            <div class="overflow-x-auto">
                <table class="data-table w-full">
                    <thead>
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Role</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pengguna</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Izin (Permissions)</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($roles as $role)
                        <tr class="group hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $role->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $role->description }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="badge badge-info rounded-full px-3">
                                    {{ $role->users_count }} pengguna
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($role->permissions->take(5) as $permission)
                                    <span class="badge badge-primary text-[10px] px-2 py-0.5">
                                        {{ $permission->name }}
                                    </span>
                                    @endforeach
                                    @if($role->permissions->count() > 5)
                                    <span class="badge bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-[10px] px-2 py-0.5">
                                        +{{ $role->permissions->count() - 5 }} lainnya
                                    </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end items-center gap-2">
                                    <a href="{{ route('roles.edit', $role) }}" class="action-btn-primary" title="Edit">
                                        <i class="ti ti-edit"></i>
                                    </a>

                                    @if ($role->users_count === 0)
                                    <form action="{{ route('roles.destroy', $role) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn-danger" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus peran ini?')">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                                        <i class="ti ti-shield-off text-3xl text-gray-400"></i>
                                    </div>
                                    <p class="text-lg font-medium">Tidak ada role ditemukan</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>