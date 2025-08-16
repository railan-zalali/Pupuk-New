@extends('layouts.app')

@section('title', 'Detail Batch Produk')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Batch Produk: {{ $product->name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Informasi Produk</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Kode Produk</th>
                                    <td>{{ $product->code }}</td>
                                </tr>
                                <tr>
                                    <th>Nama Produk</th>
                                    <td>{{ $product->name }}</td>
                                </tr>
                                <tr>
                                    <th>Kategori</th>
                                    <td>{{ $product->category->name }}</td>
                                </tr>
                                <tr>
                                    <th>Supplier</th>
                                    <td>{{ $product->supplier->name }}</td>
                                </tr>
                                <tr>
                                    <th>Stok Total</th>
                                    <td>{{ $product->stock }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h5>Batch Tersedia (FIFO - First In First Out)</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No. Batch</th>
                                            <th>Tanggal Produksi</th>
                                            <th>Tanggal Kadaluarsa</th>
                                            <th>Kuantitas Awal</th>
                                            <th>Sisa Kuantitas</th>
                                            <th>Harga Beli</th>
                                            <th>Tanggal Dibuat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($availableBatches as $batch)
                                        <tr>
                                            <td>{{ $batch->batch_number }}</td>
                                            <td>{{ $batch->production_date ? date('d/m/Y', strtotime($batch->production_date)) : '-' }}</td>
                                            <td>
                                                @if($batch->expiry_date)
                                                    <span class="{{ Carbon\Carbon::parse($batch->expiry_date)->isPast() ? 'text-danger' : (Carbon\Carbon::parse($batch->expiry_date)->diffInDays(now()) < 30 ? 'text-warning' : '') }}">
                                                        {{ date('d/m/Y', strtotime($batch->expiry_date)) }}
                                                    </span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{ $batch->quantity }}</td>
                                            <td>{{ $batch->remaining_quantity }}</td>
                                            <td>{{ number_format($batch->purchase_price, 0, ',', '.') }}</td>
                                            <td>{{ date('d/m/Y H:i', strtotime($batch->created_at)) }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada batch tersedia</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Semua Batch</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No. Batch</th>
                                            <th>Tanggal Produksi</th>
                                            <th>Tanggal Kadaluarsa</th>
                                            <th>Kuantitas Awal</th>
                                            <th>Sisa Kuantitas</th>
                                            <th>Harga Beli</th>
                                            <th>Status</th>
                                            <th>Tanggal Dibuat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($batches as $batch)
                                        <tr>
                                            <td>{{ $batch->batch_number }}</td>
                                            <td>{{ $batch->production_date ? date('d/m/Y', strtotime($batch->production_date)) : '-' }}</td>
                                            <td>
                                                @if($batch->expiry_date)
                                                    <span class="{{ Carbon\Carbon::parse($batch->expiry_date)->isPast() ? 'text-danger' : (Carbon\Carbon::parse($batch->expiry_date)->diffInDays(now()) < 30 ? 'text-warning' : '') }}">
                                                        {{ date('d/m/Y', strtotime($batch->expiry_date)) }}
                                                    </span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{ $batch->quantity }}</td>
                                            <td>{{ $batch->remaining_quantity }}</td>
                                            <td>{{ number_format($batch->purchase_price, 0, ',', '.') }}</td>
                                            <td>
                                                @if($batch->remaining_quantity <= 0)
                                                    <span class="badge badge-danger">Habis</span>
                                                @elseif($batch->remaining_quantity < $batch->quantity)
                                                    <span class="badge badge-warning">Sebagian</span>
                                                @else
                                                    <span class="badge badge-success">Penuh</span>
                                                @endif
                                            </td>
                                            <td>{{ date('d/m/Y H:i', strtotime($batch->created_at)) }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="text-center">Tidak ada batch</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection