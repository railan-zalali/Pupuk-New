<x-app-layout>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between animate-fade-in-up">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ __('Manajemen Pengguna') }}</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola akses dan hak pengguna aplikasi</p>
            </div>

            <div class="flex flex-col sm:flex-row gap-2">
                <!-- Search Form -->
                <form action="{{ route('users.index') }}" method="GET" class="relative group">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="ti ti-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="input-primary pl-10 pr-4 py-2 w-full sm:w-64 rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 focus:border-emerald-500 focus:ring-emerald-500 transition-all"
                        placeholder="Cari pengguna..."
                        onchange="this.form.submit()">
                </form>

                <a href="{{ route('users.create') }}" class="btn-primary flex items-center justify-center gap-2">
                    <i class="ti ti-user-plus"></i>
                    <span>Tambah Pengguna</span>
                </a>
            </div>
        </div>

        <!-- Alerts -->
        @if (session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 flex items-center gap-3 animate-fade-in-up">
            <i class="ti ti-check-circle text-xl"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
        @endif

        @if (session('error'))
        <div class="p-4 rounded-xl bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 flex items-center gap-3 animate-fade-in-up">
            <i class="ti ti-alert-circle text-xl"></i>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
        @endif

        <div class="card overflow-hidden animate-fade-in-up delay-100">
            <div class="overflow-x-auto">
                <table class="data-table w-full">
                    <thead>
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($users as $user)
                        <tr class="group hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-800 flex items-center justify-center text-emerald-600 dark:text-emerald-200 font-bold text-xs">
                                        {{ substr($user->name, 0, 2) }}
                                    </div>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($user->roles as $role)
                                    <span class="badge badge-primary">
                                        {{ $role->name }}
                                    </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->approval_status === 'approved')
                                <span class="badge badge-success flex items-center gap-1 w-fit">
                                    <i class="ti ti-check w-3 h-3"></i> Disetujui
                                </span>
                                @elseif($user->approval_status === 'pending')
                                <span class="badge badge-warning flex items-center gap-1 w-fit">
                                    <i class="ti ti-clock w-3 h-3"></i> Menunggu
                                </span>
                                @elseif($user->approval_status === 'rejected')
                                <span class="badge badge-danger flex items-center gap-1 w-fit">
                                    <i class="ti ti-x w-3 h-3"></i> Ditolak
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end items-center gap-2">
                                    <!-- Approval Actions -->
                                    @if($user->approval_status === 'pending')
                                    <form action="{{ route('users.approve', $user) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="action-btn-success" title="Setujui">
                                            <i class="ti ti-check"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('users.reject', $user) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="action-btn-danger" title="Tolak" onclick="return confirm('Tolak pengguna ini?')">
                                            <i class="ti ti-x"></i>
                                        </button>
                                    </form>
                                    @endif

                                    <a href="{{ route('users.edit', $user) }}" class="action-btn-primary" title="Edit">
                                        <i class="ti ti-edit"></i>
                                    </a>

                                    @if ($user->id !== 1 && $user->id !== auth()->id())
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn-danger" title="Hapus" onclick="return confirm('Hapus pengguna ini secara permanen?')">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                                        <i class="ti ti-users-off text-3xl text-gray-400"></i>
                                    </div>
                                    <p class="text-lg font-medium">Tidak ada pengguna ditemukan</p>
                                    <p class="text-sm">Coba sesuaikan pencarian Anda atau tambahkan pengguna baru.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                {{ $users->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>