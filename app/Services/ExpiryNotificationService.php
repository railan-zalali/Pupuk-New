<?php

namespace App\Services;

use App\Models\ProductBatch;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ExpiryNotificationService
{
    /**
     * Cache key untuk notifikasi kedaluwarsa
     */
    const CACHE_KEY_EXPIRY_NOTIFICATIONS = 'expiry_notifications';
    const CACHE_DURATION = 3600; // 1 jam

    /**
     * Mendapatkan semua notifikasi kedaluwarsa
     *
     * @return array
     */
    public function getExpiryNotifications()
    {
        return Cache::remember(self::CACHE_KEY_EXPIRY_NOTIFICATIONS, self::CACHE_DURATION, function () {
            return $this->generateExpiryNotifications();
        });
    }

    /**
     * Generate notifikasi kedaluwarsa
     *
     * @return array
     */
    private function generateExpiryNotifications()
    {
        $notifications = [];
        $now = now();

        // Batch yang sudah kedaluwarsa
        $expiredBatches = ProductBatch::with('product')
            ->whereNotNull('expiry_date')
            ->where('remaining_quantity', '>', 0)
            ->where('expiry_date', '<', $now)
            ->orderBy('expiry_date', 'asc')
            ->get();

        foreach ($expiredBatches as $batch) {
            $daysExpired = \Carbon\Carbon::parse($batch->expiry_date)->diffInDays($now, false);
            $notifications[] = [
                'type' => 'expired',
                'severity' => 'critical',
                'title' => 'Produk Kedaluwarsa',
                'message' => "Batch {$batch->batch_number} dari produk {$batch->product->name} telah kedaluwarsa {$daysExpired} hari yang lalu",
                'product_id' => $batch->product_id,
                'batch_id' => $batch->id,
                'expiry_date' => $batch->expiry_date,
                'remaining_quantity' => $batch->remaining_quantity,
                'days_expired' => $daysExpired,
                'action_required' => true,
                'suggested_action' => 'Segera keluarkan dari stok atau lakukan disposal'
            ];
        }

        // Batch yang akan kedaluwarsa dalam 7 hari (kritis)
        $criticalBatches = ProductBatch::with('product')
            ->whereNotNull('expiry_date')
            ->where('remaining_quantity', '>', 0)
            ->where('expiry_date', '>=', $now)
            ->where('expiry_date', '<=', $now->copy()->addDays(7))
            ->orderBy('expiry_date', 'asc')
            ->get();

        foreach ($criticalBatches as $batch) {
            $daysToExpiry = $now->diffInDays(\Carbon\Carbon::parse($batch->expiry_date), false);
            $notifications[] = [
                'type' => 'expiring_critical',
                'severity' => 'high',
                'title' => 'Akan Kedaluwarsa Segera',
                'message' => "Batch {$batch->batch_number} dari produk {$batch->product->name} akan kedaluwarsa dalam {$daysToExpiry} hari",
                'product_id' => $batch->product_id,
                'batch_id' => $batch->id,
                'expiry_date' => $batch->expiry_date,
                'remaining_quantity' => $batch->remaining_quantity,
                'days_to_expiry' => $daysToExpiry,
                'action_required' => true,
                'suggested_action' => 'Prioritaskan penjualan atau promosi khusus'
            ];
        }

        // Batch yang akan kedaluwarsa dalam 30 hari (peringatan)
        $warningBatches = ProductBatch::with('product')
            ->whereNotNull('expiry_date')
            ->where('remaining_quantity', '>', 0)
            ->where('expiry_date', '>', $now->copy()->addDays(7))
            ->where('expiry_date', '<=', $now->copy()->addDays(30))
            ->orderBy('expiry_date', 'asc')
            ->get();

        foreach ($warningBatches as $batch) {
            $daysToExpiry = $now->diffInDays(\Carbon\Carbon::parse($batch->expiry_date), false);
            $notifications[] = [
                'type' => 'expiring_warning',
                'severity' => 'medium',
                'title' => 'Akan Kedaluwarsa',
                'message' => "Batch {$batch->batch_number} dari produk {$batch->product->name} akan kedaluwarsa dalam {$daysToExpiry} hari",
                'product_id' => $batch->product_id,
                'batch_id' => $batch->id,
                'expiry_date' => $batch->expiry_date,
                'remaining_quantity' => $batch->remaining_quantity,
                'days_to_expiry' => $daysToExpiry,
                'action_required' => false,
                'suggested_action' => 'Monitor dan rencanakan strategi penjualan'
            ];
        }

        return $notifications;
    }

    /**
     * Mendapatkan ringkasan notifikasi untuk dashboard
     *
     * @return array
     */
    public function getNotificationSummary()
    {
        $notifications = $this->getExpiryNotifications();
        
        $summary = [
            'total' => count($notifications),
            'critical' => 0,
            'high' => 0,
            'medium' => 0,
            'expired' => 0,
            'expiring_7_days' => 0,
            'expiring_30_days' => 0,
        ];

        foreach ($notifications as $notification) {
            switch ($notification['severity']) {
                case 'critical':
                    $summary['critical']++;
                    break;
                case 'high':
                    $summary['high']++;
                    break;
                case 'medium':
                    $summary['medium']++;
                    break;
            }

            switch ($notification['type']) {
                case 'expired':
                    $summary['expired']++;
                    break;
                case 'expiring_critical':
                    $summary['expiring_7_days']++;
                    break;
                case 'expiring_warning':
                    $summary['expiring_30_days']++;
                    break;
            }
        }

        return $summary;
    }

    /**
     * Mendapatkan notifikasi berdasarkan tingkat keparahan
     *
     * @param string $severity
     * @return array
     */
    public function getNotificationsBySeverity($severity)
    {
        $notifications = $this->getExpiryNotifications();
        
        return array_filter($notifications, function ($notification) use ($severity) {
            return $notification['severity'] === $severity;
        });
    }

    /**
     * Mendapatkan notifikasi untuk produk tertentu
     *
     * @param int $productId
     * @return array
     */
    public function getNotificationsForProduct($productId)
    {
        $notifications = $this->getExpiryNotifications();
        
        return array_filter($notifications, function ($notification) use ($productId) {
            return $notification['product_id'] === $productId;
        });
    }

    /**
     * Menghapus cache notifikasi (untuk refresh manual)
     *
     * @return void
     */
    public function clearNotificationCache()
    {
        Cache::forget(self::CACHE_KEY_EXPIRY_NOTIFICATIONS);
    }

    /**
     * Menandai batch sebagai sudah ditangani (untuk mengurangi notifikasi)
     *
     * @param int $batchId
     * @param string $action
     * @return void
     */
    public function markBatchHandled($batchId, $action = 'handled')
    {
        // Log aksi yang diambil
        Log::info("Batch {$batchId} telah ditangani dengan aksi: {$action}");
        
        // Clear cache untuk refresh notifikasi
        $this->clearNotificationCache();
    }

    /**
     * Mendapatkan rekomendasi aksi untuk batch yang akan kedaluwarsa
     *
     * @param int $batchId
     * @return array
     */
    public function getActionRecommendations($batchId)
    {
        $batch = ProductBatch::with('product')->find($batchId);
        
        if (!$batch || !$batch->expiry_date) {
            return [];
        }

        $now = now();
        $daysToExpiry = $now->diffInDays($batch->expiry_date, false);
        
        $recommendations = [];

        if ($daysToExpiry < 0) {
            // Sudah kedaluwarsa
            $recommendations = [
                'priority' => 'critical',
                'actions' => [
                    'Segera keluarkan dari stok aktif',
                    'Lakukan disposal sesuai prosedur',
                    'Dokumentasikan kerugian',
                    'Review proses inventory management'
                ]
            ];
        } elseif ($daysToExpiry <= 3) {
            // Kritis (3 hari atau kurang)
            $recommendations = [
                'priority' => 'high',
                'actions' => [
                    'Berikan diskon besar-besaran (30-50%)',
                    'Tawarkan ke pelanggan tetap dengan harga khusus',
                    'Pertimbangkan untuk promosi bundle',
                    'Siapkan rencana disposal jika tidak terjual'
                ]
            ];
        } elseif ($daysToExpiry <= 7) {
            // Mendesak (7 hari atau kurang)
            $recommendations = [
                'priority' => 'medium',
                'actions' => [
                    'Berikan diskon 15-25%',
                    'Promosikan di media sosial',
                    'Tawarkan ke pelanggan grosir',
                    'Pertimbangkan untuk flash sale'
                ]
            ];
        } elseif ($daysToExpiry <= 30) {
            // Peringatan (30 hari atau kurang)
            $recommendations = [
                'priority' => 'low',
                'actions' => [
                    'Monitor penjualan lebih ketat',
                    'Pertimbangkan promosi ringan',
                    'Informasikan ke tim sales',
                    'Rencanakan strategi marketing'
                ]
            ];
        }

        return $recommendations;
    }
}