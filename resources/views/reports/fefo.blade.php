<x-app-layout>
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Laporan FEFO</h1>
                    <p class="text-muted">Analisis komprehensif kadaluarsa produk dengan sistem First Expired, First Out</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-primary" onclick="exportReport('pdf')">
                        <i class="fas fa-file-pdf"></i> Export PDF
                    </button>
                    <button type="button" class="btn btn-outline-success" onclick="exportReport('excel')">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('reports.fefo') }}" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control" 
                               value="{{ $startDate->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tanggal Akhir</label>
                        <input type="date" name="end_date" class="form-control" 
                               value="{{ $endDate->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Kategori</label>
                        <select name="category" class="form-select">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $category == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status Kadaluarsa</label>
                        <select name="severity" class="form-select">
                            <option value="all" {{ $severity == 'all' ? 'selected' : '' }}>Semua</option>
                            <option value="expired" {{ $severity == 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
                            <option value="critical" {{ $severity == 'critical' ? 'selected' : '' }}>Kritis (≤7 hari)</option>
                            <option value="warning" {{ $severity == 'warning' ? 'selected' : '' }}>Peringatan (≤30 hari)</option>
                            <option value="fresh" {{ $severity == 'fresh' ? 'selected' : '' }}>Segar (>30 hari)</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Cari Produk</label>
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Nama/Kode produk..." value="{{ $search }}">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Metrics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-danger">
                <div class="card-body text-center">
                    <div class="text-danger">
                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                    </div>
                    <h5 class="card-title text-danger">Kadaluarsa</h5>
                    <h3 class="text-danger">Rp {{ number_format($metrics['expired_value'], 0, ',', '.') }}</h3>
                    <small class="text-muted">{{ number_format($metrics['expired_percentage'], 1) }}% dari total</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <div class="text-warning">
                        <i class="fas fa-clock fa-2x mb-2"></i>
                    </div>
                    <h5 class="card-title text-warning">Kritis</h5>
                    <h3 class="text-warning">Rp {{ number_format($metrics['critical_value'], 0, ',', '.') }}</h3>
                    <small class="text-muted">{{ number_format($metrics['critical_percentage'], 1) }}% dari total</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-info">
                <div class="card-body text-center">
                    <div class="text-info">
                        <i class="fas fa-info-circle fa-2x mb-2"></i>
                    </div>
                    <h5 class="card-title text-info">Peringatan</h5>
                    <h3 class="text-info">Rp {{ number_format($metrics['warning_value'], 0, ',', '.') }}</h3>
                    <small class="text-muted">{{ number_format($metrics['warning_percentage'], 1) }}% dari total</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body text-center">
                    <div class="text-success">
                        <i class="fas fa-check-circle fa-2x mb-2"></i>
                    </div>
                    <h5 class="card-title text-success">Total Nilai</h5>
                    <h3 class="text-success">Rp {{ number_format($metrics['total_value'], 0, ',', '.') }}</h3>
                    <small class="text-muted">{{ $metrics['total_products'] }} produk, {{ $metrics['total_batches'] }} batch</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Risk Analysis -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Analisis Risiko Kadaluarsa</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="progress mb-3" style="height: 30px;">
                        <div class="progress-bar bg-danger" role="progressbar" 
                             style="width: {{ $metrics['expired_percentage'] }}%"
                             title="Kadaluarsa: {{ number_format($metrics['expired_percentage'], 1) }}%">
                            @if($metrics['expired_percentage'] > 5)
                                {{ number_format($metrics['expired_percentage'], 1) }}%
                            @endif
                        </div>
                        <div class="progress-bar bg-warning" role="progressbar" 
                             style="width: {{ $metrics['critical_percentage'] }}%"
                             title="Kritis: {{ number_format($metrics['critical_percentage'], 1) }}%">
                            @if($metrics['critical_percentage'] > 5)
                                {{ number_format($metrics['critical_percentage'], 1) }}%
                            @endif
                        </div>
                        <div class="progress-bar bg-info" role="progressbar" 
                             style="width: {{ $metrics['warning_percentage'] }}%"
                             title="Peringatan: {{ number_format($metrics['warning_percentage'], 1) }}%">
                            @if($metrics['warning_percentage'] > 5)
                                {{ number_format($metrics['warning_percentage'], 1) }}%
                            @endif
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <small class="text-danger">
                            <i class="fas fa-square"></i> Kadaluarsa ({{ number_format($metrics['expired_percentage'], 1) }}%)
                        </small>
                        <small class="text-warning">
                            <i class="fas fa-square"></i> Kritis ({{ number_format($metrics['critical_percentage'], 1) }}%)
                        </small>
                        <small class="text-info">
                            <i class="fas fa-square"></i> Peringatan ({{ number_format($metrics['warning_percentage'], 1) }}%)
                        </small>
                        <small class="text-success">
                            <i class="fas fa-square"></i> Segar ({{ number_format(100 - $metrics['expired_percentage'] - $metrics['critical_percentage'] - $metrics['warning_percentage'], 1) }}%)
                        </small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <h4 class="mb-1">Tingkat Risiko</h4>
                        @if($metrics['risk_percentage'] > 20)
                            <span class="badge bg-danger fs-6">TINGGI ({{ number_format($metrics['risk_percentage'], 1) }}%)</span>
                        @elseif($metrics['risk_percentage'] > 10)
                            <span class="badge bg-warning fs-6">SEDANG ({{ number_format($metrics['risk_percentage'], 1) }}%)</span>
                        @else
                            <span class="badge bg-success fs-6">RENDAH ({{ number_format($metrics['risk_percentage'], 1) }}%)</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Detail Produk FEFO</h5>
        </div>
        <div class="card-body">
            @if(count($fefoData) > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Produk</th>
                                <th>Kategori</th>
                                <th>Total Batch</th>
                                <th>Qty Total</th>
                                <th>Nilai Total</th>
                                <th>Kadaluarsa</th>
                                <th>Kritis</th>
                                <th>Peringatan</th>
                                <th>Segar</th>
                                <th>Prioritas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($fefoData as $item)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{ $item['product']->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $item['product']->code }}</small>
                                        </div>
                                    </td>
                                    <td>{{ $item['product']->category->name ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ count($item['batches']) }}</span>
                                    </td>
                                    <td>{{ number_format($item['total_quantity'], 0, ',', '.') }} {{ $item['product']->unit }}</td>
                                    <td>Rp {{ number_format($item['total_value'], 0, ',', '.') }}</td>
                                    <td>
                                        @if($item['expired_quantity'] > 0)
                                            <span class="badge bg-danger">{{ number_format($item['expired_quantity'], 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item['critical_quantity'] > 0)
                                            <span class="badge bg-warning">{{ number_format($item['critical_quantity'], 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item['warning_quantity'] > 0)
                                            <span class="badge bg-info">{{ number_format($item['warning_quantity'], 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item['fresh_quantity'] > 0)
                                            <span class="badge bg-success">{{ number_format($item['fresh_quantity'], 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item['priority_score'] >= 100)
                                            <span class="badge bg-danger">KRITIS</span>
                                        @elseif($item['priority_score'] >= 50)
                                            <span class="badge bg-warning">TINGGI</span>
                                        @elseif($item['priority_score'] >= 20)
                                            <span class="badge bg-info">SEDANG</span>
                                        @else
                                            <span class="badge bg-success">RENDAH</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                                onclick="showBatchDetails({{ $item['product']->id }})">
                                            <i class="fas fa-eye"></i> Detail
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Tidak ada data produk dengan batch kadaluarsa</h5>
                    <p class="text-muted">Coba ubah filter pencarian atau periode tanggal</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Batch Details Modal -->
<div class="modal fade" id="batchDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Batch FEFO</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="batchDetailsContent">
                <div class="text-center py-3">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Auto-submit form on filter change
document.querySelectorAll('#filterForm select, #filterForm input[type="date"]').forEach(element => {
    element.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
});

// Export functions
function exportReport(type) {
    const form = document.getElementById('filterForm');
    const formData = new FormData(form);
    formData.append('type', type);
    
    const params = new URLSearchParams(formData);
    window.open(`{{ route('reports.fefo') }}?${params.toString()}`, '_blank');
}

// Show batch details
function showBatchDetails(productId) {
    const modal = new bootstrap.Modal(document.getElementById('batchDetailsModal'));
    const content = document.getElementById('batchDetailsContent');
    
    // Show loading
    content.innerHTML = `
        <div class="text-center py-3">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `;
    
    modal.show();
    
    // Find product data
    const productData = @json($fefoData).find(item => item.product.id === productId);
    
    if (productData) {
        let batchesHtml = `
            <div class="row mb-3">
                <div class="col-md-6">
                    <h6><strong>${productData.product.name}</strong></h6>
                    <p class="text-muted mb-0">Kode: ${productData.product.code}</p>
                    <p class="text-muted">Kategori: ${productData.product.category?.name || '-'}</p>
                </div>
                <div class="col-md-6 text-end">
                    <p class="mb-1">Total Quantity: <strong>${new Intl.NumberFormat('id-ID').format(productData.total_quantity)} ${productData.product.unit}</strong></p>
                    <p class="mb-0">Total Value: <strong>Rp ${new Intl.NumberFormat('id-ID').format(productData.total_value)}</strong></p>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead class="table-dark">
                        <tr>
                            <th>Batch</th>
                            <th>Tanggal Kadaluarsa</th>
                            <th>Hari Tersisa</th>
                            <th>Quantity</th>
                            <th>Nilai</th>
                            <th>Status</th>
                            <th>Prioritas</th>
                            <th>Rekomendasi</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        productData.batches.forEach(batchData => {
            const batch = batchData.batch;
            const expiryDate = batch.expiry_date ? new Date(batch.expiry_date).toLocaleDateString('id-ID') : '-';
            const daysToExpiry = batchData.days_to_expiry;
            
            let statusBadge = '';
            let priorityBadge = '';
            
            switch(batchData.status) {
                case 'expired':
                    statusBadge = '<span class="badge bg-danger">Kadaluarsa</span>';
                    break;
                case 'critical':
                    statusBadge = '<span class="badge bg-warning">Kritis</span>';
                    break;
                case 'warning':
                    statusBadge = '<span class="badge bg-info">Peringatan</span>';
                    break;
                case 'fresh':
                    statusBadge = '<span class="badge bg-success">Segar</span>';
                    break;
                default:
                    statusBadge = '<span class="badge bg-secondary">-</span>';
            }
            
            switch(batchData.priority) {
                case 'critical':
                    priorityBadge = '<span class="badge bg-danger">Kritis</span>';
                    break;
                case 'urgent':
                    priorityBadge = '<span class="badge bg-warning">Mendesak</span>';
                    break;
                case 'high':
                    priorityBadge = '<span class="badge bg-warning">Tinggi</span>';
                    break;
                case 'medium':
                    priorityBadge = '<span class="badge bg-info">Sedang</span>';
                    break;
                case 'low':
                    priorityBadge = '<span class="badge bg-success">Rendah</span>';
                    break;
                default:
                    priorityBadge = '<span class="badge bg-secondary">-</span>';
            }
            
            batchesHtml += `
                <tr>
                    <td>${batch.batch_number || 'N/A'}</td>
                    <td>${expiryDate}</td>
                    <td>
                        ${daysToExpiry !== null ? 
                            (daysToExpiry < 0 ? 
                                `<span class="text-danger">${Math.abs(daysToExpiry)} hari lalu</span>` : 
                                `${daysToExpiry} hari`
                            ) : '-'
                        }
                    </td>
                    <td>${new Intl.NumberFormat('id-ID').format(batch.remaining_quantity)} ${productData.product.unit}</td>
                    <td>Rp ${new Intl.NumberFormat('id-ID').format(batchData.value)}</td>
                    <td>${statusBadge}</td>
                    <td>${priorityBadge}</td>
                    <td><small>${batchData.recommended_action}</small></td>
                </tr>
            `;
        });
        
        batchesHtml += `
                    </tbody>
                </table>
            </div>
        `;
        
        content.innerHTML = batchesHtml;
    }
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
</x-app-layout>