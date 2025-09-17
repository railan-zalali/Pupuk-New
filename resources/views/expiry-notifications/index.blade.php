<x-app-layout>
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">
                <i class="fas fa-exclamation-triangle text-orange-500 mr-2"></i>
                Notifikasi Kedaluwarsa
            </h1>
            <p class="text-gray-600">Kelola dan pantau produk yang akan atau sudah kedaluwarsa</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-2 mt-4 md:mt-0">
            <button onclick="refreshNotifications()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-sync-alt mr-2"></i>
                Refresh
            </button>
            <a href="{{ route('reports.fifo-stock') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200 text-center">
                <i class="fas fa-chart-bar mr-2"></i>
                Laporan FIFO
            </a>
        </div>
    </div>

    @if(isset($error))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <strong>Error:</strong> {{ $error }}
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-times-circle text-red-500 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Sudah Kedaluwarsa</p>
                    <p class="text-2xl font-bold text-red-600">{{ $summary['expired'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">Perlu tindakan segera</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-orange-500 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Kritis (≤7 hari)</p>
                    <p class="text-2xl font-bold text-orange-600">{{ $summary['expiring_7_days'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">Prioritas tinggi</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-clock text-yellow-500 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Peringatan (≤30 hari)</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $summary['expiring_30_days'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">Perlu monitoring</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-list text-blue-500 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Notifikasi</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $summary['total'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">Semua kategori</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Filter Notifikasi</h3>
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('expiry-notifications.index') }}" 
               class="px-4 py-2 rounded-lg transition duration-200 {{ !request('severity') && !request('type') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                <i class="fas fa-list mr-2"></i>
                Semua
            </a>
            <a href="{{ route('expiry-notifications.index', ['severity' => 'critical']) }}" 
               class="px-4 py-2 rounded-lg transition duration-200 {{ request('severity') === 'critical' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                <i class="fas fa-times-circle mr-2"></i>
                Kritis
            </a>
            <a href="{{ route('expiry-notifications.index', ['severity' => 'high']) }}" 
               class="px-4 py-2 rounded-lg transition duration-200 {{ request('severity') === 'high' ? 'bg-orange-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Tinggi
            </a>
            <a href="{{ route('expiry-notifications.index', ['severity' => 'medium']) }}" 
               class="px-4 py-2 rounded-lg transition duration-200 {{ request('severity') === 'medium' ? 'bg-yellow-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                <i class="fas fa-clock mr-2"></i>
                Sedang
            </a>
            <a href="{{ route('expiry-notifications.index', ['type' => 'expired']) }}" 
               class="px-4 py-2 rounded-lg transition duration-200 {{ request('type') === 'expired' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                <i class="fas fa-ban mr-2"></i>
                Sudah Kedaluwarsa
            </a>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">
                Daftar Notifikasi
                @if(request('severity'))
                    - {{ ucfirst(request('severity')) }}
                @endif
                @if(request('type'))
                    - {{ ucfirst(str_replace('_', ' ', request('type'))) }}
                @endif
            </h3>
        </div>

        @if(!empty($notifications))
            <div class="divide-y divide-gray-200">
                @foreach($notifications as $notification)
                    <div class="p-6 hover:bg-gray-50 transition duration-200">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium mr-3
                                        @if($notification['severity'] === 'critical') bg-red-100 text-red-800
                                        @elseif($notification['severity'] === 'high') bg-orange-100 text-orange-800
                                        @else bg-yellow-100 text-yellow-800
                                        @endif">
                                        @if($notification['severity'] === 'critical')
                                            <i class="fas fa-times-circle mr-1"></i>
                                        @elseif($notification['severity'] === 'high')
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                        @else
                                            <i class="fas fa-clock mr-1"></i>
                                        @endif
                                        {{ ucfirst($notification['severity']) }}
                                    </span>
                                    
                                    @if($notification['action_required'])
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-exclamation mr-1"></i>
                                            Perlu Tindakan
                                        </span>
                                    @endif
                                </div>

                                <h4 class="text-lg font-medium text-gray-900 mb-1">{{ $notification['title'] }}</h4>
                                <p class="text-gray-700 mb-3">{{ $notification['message'] }}</p>

                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm text-gray-600">
                                    <div>
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        <strong>Tanggal Kedaluwarsa:</strong><br>
                                        {{ \Carbon\Carbon::parse($notification['expiry_date'])->format('d/m/Y') }}
                                    </div>
                                    <div>
                                        <i class="fas fa-boxes mr-1"></i>
                                        <strong>Sisa Stok:</strong><br>
                                        {{ number_format($notification['remaining_quantity'], 0, ',', '.') }} unit
                                    </div>
                                    <div>
                                        <i class="fas fa-clock mr-1"></i>
                                        <strong>Status:</strong><br>
                                        @if(isset($notification['days_expired']))
                                            Kedaluwarsa {{ ceil($notification['days_expired']) }} hari
                                        @else
                                            {{ ceil($notification['days_to_expiry']) }} hari lagi
                                        @endif
                                    </div>
                                    <div>
                                        <i class="fas fa-barcode mr-1"></i>
                                        <strong>Batch:</strong><br>
                                        {{ $notification['batch_id'] ?? 'N/A' }}
                                    </div>
                                </div>

                                @if(isset($notification['suggested_action']))
                                    <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                        <p class="text-sm text-blue-800">
                                            <i class="fas fa-lightbulb mr-1"></i>
                                            <strong>Saran Tindakan:</strong> {{ $notification['suggested_action'] }}
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <div class="ml-6 flex flex-col gap-2">
                                <button onclick="showRecommendations({{ $notification['batch_id'] }})" 
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm transition duration-200">
                                    <i class="fas fa-lightbulb mr-1"></i>
                                    Rekomendasi
                                </button>
                                
                                @if($notification['action_required'])
                                    <button onclick="markAsHandled({{ $notification['batch_id'] }})" 
                                            class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm transition duration-200">
                                        <i class="fas fa-check mr-1"></i>
                                        Tandai Selesai
                                    </button>
                                @endif
                                
                                <a href="{{ route('products.batches', $notification['product_id']) }}" 
                                   class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded text-sm transition duration-200 text-center">
                                    <i class="fas fa-eye mr-1"></i>
                                    Lihat Batch
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-12 text-center">
                <i class="fas fa-check-circle text-green-500 text-6xl mb-4"></i>
                <h3 class="text-xl font-medium text-gray-900 mb-2">Tidak Ada Notifikasi</h3>
                <p class="text-gray-600">
                    @if(request('severity') || request('type'))
                        Tidak ada notifikasi untuk filter yang dipilih.
                    @else
                        Semua produk dalam kondisi baik, tidak ada yang akan atau sudah kedaluwarsa.
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>

<!-- Recommendations Modal -->
<div id="recommendationsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Rekomendasi Tindakan</h3>
                    <button onclick="closeRecommendationsModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div id="recommendationsContent">
                    <div class="text-center py-4">
                        <i class="fas fa-spinner fa-spin text-blue-500 text-2xl"></i>
                        <p class="text-gray-600 mt-2">Memuat rekomendasi...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function refreshNotifications() {
    const btn = event.target.closest('button');
    const originalContent = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Loading...';
    btn.disabled = true;

    fetch('{{ route("expiry-notifications.refresh") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert('Gagal me-refresh notifikasi: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat me-refresh notifikasi');
    })
    .finally(() => {
        btn.innerHTML = originalContent;
        btn.disabled = false;
    });
}

function showRecommendations(batchId) {
    document.getElementById('recommendationsModal').classList.remove('hidden');
    
    fetch(`{{ url('/expiry-notifications/recommendations') }}/${batchId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const recommendations = data.data;
                let content = `
                    <div class="mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            ${recommendations.priority === 'critical' ? 'bg-red-100 text-red-800' : 
                              recommendations.priority === 'high' ? 'bg-orange-100 text-orange-800' : 
                              recommendations.priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 
                              'bg-blue-100 text-blue-800'}">
                            Prioritas: ${recommendations.priority.charAt(0).toUpperCase() + recommendations.priority.slice(1)}
                        </span>
                    </div>
                    <div class="space-y-2">
                        <h4 class="font-medium text-gray-900">Tindakan yang Disarankan:</h4>
                        <ul class="list-disc list-inside space-y-1 text-sm text-gray-700">
                `;
                
                recommendations.actions.forEach(action => {
                    content += `<li>${action}</li>`;
                });
                
                content += `
                        </ul>
                    </div>
                `;
                
                document.getElementById('recommendationsContent').innerHTML = content;
            } else {
                document.getElementById('recommendationsContent').innerHTML = `
                    <div class="text-center py-4">
                        <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
                        <p class="text-red-600 mt-2">${data.message}</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('recommendationsContent').innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
                    <p class="text-red-600 mt-2">Terjadi kesalahan saat memuat rekomendasi</p>
                </div>
            `;
        });
}

function closeRecommendationsModal() {
    document.getElementById('recommendationsModal').classList.add('hidden');
}

function markAsHandled(batchId) {
    if (!confirm('Apakah Anda yakin batch ini sudah ditangani?')) {
        return;
    }

    const action = prompt('Masukkan tindakan yang telah dilakukan:', 'handled');
    if (!action) return;

    fetch(`{{ url('/expiry-notifications/batch') }}/${batchId}/mark-handled`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ action: action })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Batch berhasil ditandai sebagai sudah ditangani');
            window.location.reload();
        } else {
            alert('Gagal menandai batch: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menandai batch');
    });
}

// Close modal when clicking outside
document.getElementById('recommendationsModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRecommendationsModal();
    }
});
</script>
@endpush
</x-app-layout>