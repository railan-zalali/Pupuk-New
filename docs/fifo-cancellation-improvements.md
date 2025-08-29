# Perbaikan Penanganan FIFO pada Pembatalan Transaksi

## Latar Belakang

Sistem penjualan saat ini menggunakan metode FIFO (First In, First Out) untuk pengurangan stok saat transaksi penjualan. Namun, pada proses pembatalan transaksi, stok dikembalikan tanpa memperhatikan batch mana yang seharusnya diperbarui. Hal ini dapat menyebabkan inkonsistensi data stok dan batch.

## Masalah yang Diselesaikan

1. **Inkonsistensi Data Batch**: Pembatalan transaksi sebelumnya hanya menambahkan stok produk secara keseluruhan tanpa memperbarui data batch yang spesifik.

2. **Tidak Ada Pelacakan Batch**: Tidak ada informasi batch mana yang diperbarui saat pembatalan transaksi.

3. **Potensi Kesalahan Data**: Jika batch yang digunakan dalam transaksi sudah kedaluwarsa atau memiliki masalah lain, pengembalian stok secara umum dapat menyebabkan data yang tidak akurat.

## Solusi yang Diimplementasikan

### 1. Penambahan Metode `restoreStock` di FifoService

Metode baru `restoreStock` ditambahkan ke `FifoService` untuk menangani pengembalian stok dengan benar menggunakan prinsip FIFO. Metode ini:

- Mencari pergerakan stok keluar yang terkait dengan transaksi yang dibatalkan
- Mengembalikan stok ke batch yang sesuai berdasarkan pergerakan stok sebelumnya
- Mencatat pergerakan stok masuk dengan referensi ke batch yang tepat
- Menangani kasus di mana tidak ada pergerakan stok yang ditemukan

```php
public function restoreStock($productId, $quantity, $referenceType, $referenceId, $notes = '')
{
    // Implementasi pengembalian stok dengan FIFO
    // ...
}
```

### 2. Pembaruan Metode `destroy` di SaleController

Metode `destroy` di `SaleController` diperbarui untuk menggunakan `FifoService::restoreStock` saat membatalkan transaksi penjualan atau draft. Perubahan ini:

- Menggantikan logika pengembalian stok yang lama dengan panggilan ke `FifoService::restoreStock`
- Memastikan stok dikembalikan ke batch yang tepat
- Menjaga konsistensi data stok dan batch

```php
public function destroy(Sale $sale)
{
    try {
        DB::beginTransaction();

        // Restore stock for both completed sales and drafts
        $sale->load(['saleDetails.product']);
        $productIds = [];
        $fifoService = app(FifoService::class);

        foreach ($sale->saleDetails as $detail) {
            $product = $detail->product;
            $productIds[] = $product->id;
            $referenceType = $sale->status === 'draft' ? 'draft_void' : 'sale_void';
            $notes = $sale->status === 'draft' ? 'Draft dibatalkan' : 'Transaksi dibatalkan';

            // Gunakan FifoService untuk mengembalikan stok dengan benar
            $fifoService->restoreStock(
                $product->id,
                $detail->base_quantity,
                $referenceType,
                $sale->id,
                $notes
            );
        }

        // Kode lainnya...
    }
}
```

## Manfaat Implementasi

1. **Konsistensi Data**: Memastikan data stok dan batch tetap konsisten setelah pembatalan transaksi.

2. **Pelacakan yang Lebih Baik**: Memberikan pelacakan yang lebih baik tentang batch mana yang diperbarui saat pembatalan transaksi.

3. **Keakuratan Laporan**: Meningkatkan keakuratan laporan stok dan pergerakan stok.

4. **Penanganan Kasus Khusus**: Menangani kasus di mana tidak ada pergerakan stok yang ditemukan dengan cara yang lebih baik.

## Pengujian

Untuk memastikan implementasi berfungsi dengan benar, lakukan pengujian berikut:

1. **Pembatalan Transaksi Reguler**:
   - Buat transaksi penjualan dengan beberapa produk
   - Batalkan transaksi
   - Verifikasi bahwa stok dikembalikan ke batch yang tepat

2. **Pembatalan Draft**:
   - Buat draft penjualan
   - Batalkan draft
   - Verifikasi bahwa stok dikembalikan dengan benar

3. **Kasus Khusus**:
   - Uji pembatalan transaksi lama di mana pergerakan stok mungkin tidak ditemukan
   - Verifikasi bahwa stok tetap dikembalikan dengan benar

## Catatan Implementasi

- Implementasi ini memerlukan transaksi database untuk memastikan konsistensi data
- Pergerakan stok dicatat dengan referensi ke batch yang tepat
- Jika tidak ada pergerakan stok yang ditemukan, stok dikembalikan secara langsung tanpa referensi batch