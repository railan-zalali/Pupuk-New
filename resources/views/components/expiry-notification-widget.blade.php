<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-800">
            <i class="fas fa-exclamation-triangle text-orange-500 mr-2"></i>
            Notifikasi Kedaluwarsa
        </h3>
        <button onclick="refreshExpiryNotifications()" class="text-blue-600 hover:text-blue-800 text-sm">
            <i class="fas fa-sync-alt mr-1"></i>
            Refresh
        </button>
    </div>

    @if(isset($error))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <strong>Error:</strong> {{ $error }}
        </div>
    @endif

    <!-- Summary Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-red-50 border border-red-200 rounded-lg p-3">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-times-circle text-red-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">Kedaluwarsa</p>
                    <p class="text-lg font-bold text-red-900">{{ $summary['critical'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-orange-50 border border-orange-200 rounded-lg p-3">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-orange-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-orange-800">Kritis (7 hari)</p>
                    <p class="text-lg font-bold text-orange-900">{{ $summary['high'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-clock text-yellow-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-yellow-800">Peringatan (30 hari)</p>
                    <p class="text-lg font-bold text-yellow-900">{{ $summary['medium'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-list text-blue-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-blue-800">Total</p>
                    <p class="text-lg font-bold text-blue-900">{{ $summary['total'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Notifications -->
    @if(!empty($topNotifications))
        <div class="space-y-3">
            <h4 class="text-md font-medium text-gray-700 mb-3">Notifikasi Terpenting</h4>
            @foreach($topNotifications as $notification)
                <div class="border-l-4 
                    @if($notification['severity'] === 'critical') border-red-500 bg-red-50
                    @elseif($notification['severity'] === 'high') border-orange-500 bg-orange-50
                    @else border-yellow-500 bg-yellow-50
                    @endif
                    p-4 rounded-r-lg">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h5 class="font-medium 
                                @if($notification['severity'] === 'critical') text-red-800
                                @elseif($notification['severity'] === 'high') text-orange-800
                                @else text-yellow-800
                                @endif">
                                {{ $notification['title'] }}
                            </h5>
                            <p class="text-sm 
                                @if($notification['severity'] === 'critical') text-red-700
                                @elseif($notification['severity'] === 'high') text-orange-700
                                @else text-yellow-700
                                @endif mt-1">
                                {{ $notification['message'] }}
                            </p>
                            <div class="flex items-center mt-2 text-xs 
                                @if($notification['severity'] === 'critical') text-red-600
                                @elseif($notification['severity'] === 'high') text-orange-600
                                @else text-yellow-600
                                @endif">
                                <i class="fas fa-calendar-alt mr-1"></i>
                                {{ \Carbon\Carbon::parse($notification['expiry_date'])->format('d/m/Y') }}
                                <span class="mx-2">•</span>
                                <i class="fas fa-boxes mr-1"></i>
                                {{ number_format($notification['remaining_quantity'], 0, ',', '.') }} unit
                            </div>
                        </div>
                        <div class="ml-4">
                            @if($notification['action_required'])
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-exclamation mr-1"></i>
                                    Perlu Tindakan
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    @if(isset($notification['suggested_action']))
                        <div class="mt-3 p-2 bg-white rounded border-l-2 
                            @if($notification['severity'] === 'critical') border-red-300
                            @elseif($notification['severity'] === 'high') border-orange-300
                            @else border-yellow-300
                            @endif">
                            <p class="text-xs text-gray-600">
                                <i class="fas fa-lightbulb mr-1"></i>
                                <strong>Saran:</strong> {{ $notification['suggested_action'] }}
                            </p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-8">
            <i class="fas fa-check-circle text-green-500 text-4xl mb-3"></i>
            <p class="text-gray-600">Tidak ada notifikasi kedaluwarsa saat ini</p>
            <p class="text-sm text-gray-500 mt-1">Semua produk dalam kondisi baik</p>
        </div>
    @endif

    <!-- Action Buttons -->
    <div class="mt-6 flex flex-col sm:flex-row gap-3">
        <a href="{{ route('expiry-notifications.index') }}" 
           class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-lg transition duration-200">
            <i class="fas fa-list mr-2"></i>
            Lihat Semua Notifikasi
        </a>
        
        @if(($summary['critical'] ?? 0) > 0 || ($summary['high'] ?? 0) > 0)
            <a href="{{ route('expiry-notifications.index', ['severity' => 'critical']) }}" 
               class="flex-1 bg-red-600 hover:bg-red-700 text-white text-center py-2 px-4 rounded-lg transition duration-200">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Tangani Kritis
            </a>
        @endif
    </div>
</div>

<script>
function refreshExpiryNotifications() {
    // Show loading state
    const refreshBtn = event.target.closest('button');
    const originalContent = refreshBtn.innerHTML;
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Loading...';
    refreshBtn.disabled = true;

    // Make AJAX request to refresh
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
            // Reload the page to show updated data
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
        // Restore button state
        refreshBtn.innerHTML = originalContent;
        refreshBtn.disabled = false;
    });
}

// Auto-refresh every 5 minutes
setInterval(() => {
    fetch('{{ route("api.expiry-notifications.summary") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update summary numbers without full page reload
                const summary = data.data.notifications;
                document.querySelector('.bg-red-50 .text-lg').textContent = summary.critical || 0;
                document.querySelector('.bg-orange-50 .text-lg').textContent = summary.high || 0;
                document.querySelector('.bg-yellow-50 .text-lg').textContent = summary.medium || 0;
                document.querySelector('.bg-blue-50 .text-lg').textContent = summary.total || 0;
            }
        })
        .catch(error => console.error('Auto-refresh error:', error));
}, 300000); // 5 minutes
</script>