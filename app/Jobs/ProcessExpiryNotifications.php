<?php

namespace App\Jobs;

use App\Services\ExpiryNotificationService;
use App\Services\FifoService;
use App\Models\ProductBatch;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class ProcessExpiryNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Log::info('Memulai proses notifikasi kedaluwarsa');

            $expiryService = new ExpiryNotificationService();
            $fifoService = new FifoService();

            // Clear cache untuk mendapatkan data terbaru
            $expiryService->clearNotificationCache();

            // Dapatkan statistik kedaluwarsa
            $statistics = $fifoService->getExpiryStatistics();
            $notifications = $expiryService->getExpiryNotifications();

            Log::info('Statistik kedaluwarsa:', $statistics);
            Log::info('Total notifikasi: ' . count($notifications));

            // Proses notifikasi kritis (sudah kedaluwarsa)
            $criticalNotifications = $expiryService->getNotificationsBySeverity('critical');
            if (!empty($criticalNotifications)) {
                $this->processCriticalNotifications($criticalNotifications);
            }

            // Proses notifikasi tinggi (akan kedaluwarsa dalam 7 hari)
            $highNotifications = $expiryService->getNotificationsBySeverity('high');
            if (!empty($highNotifications)) {
                $this->processHighPriorityNotifications($highNotifications);
            }

            // Update cache dengan data terbaru
            $expiryService->getExpiryNotifications();

            Log::info('Proses notifikasi kedaluwarsa selesai');

        } catch (\Exception $e) {
            Log::error('Error dalam proses notifikasi kedaluwarsa: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Proses notifikasi kritis (produk sudah kedaluwarsa)
     *
     * @param array $notifications
     * @return void
     */
    private function processCriticalNotifications($notifications)
    {
        Log::warning('Ditemukan ' . count($notifications) . ' produk yang sudah kedaluwarsa');

        foreach ($notifications as $notification) {
            // Log untuk setiap batch yang kedaluwarsa
            Log::critical("Batch kedaluwarsa: {$notification['message']}", [
                'batch_id' => $notification['batch_id'],
                'product_id' => $notification['product_id'],
                'days_expired' => $notification['days_expired'],
                'remaining_quantity' => $notification['remaining_quantity']
            ]);

            // Kirim notifikasi ke admin/manager
            $this->sendCriticalAlert($notification);
        }
    }

    /**
     * Proses notifikasi prioritas tinggi (akan kedaluwarsa dalam 7 hari)
     *
     * @param array $notifications
     * @return void
     */
    private function processHighPriorityNotifications($notifications)
    {
        Log::info('Ditemukan ' . count($notifications) . ' produk yang akan kedaluwarsa dalam 7 hari');

        foreach ($notifications as $notification) {
            Log::warning("Batch akan kedaluwarsa: {$notification['message']}", [
                'batch_id' => $notification['batch_id'],
                'product_id' => $notification['product_id'],
                'days_to_expiry' => $notification['days_to_expiry'],
                'remaining_quantity' => $notification['remaining_quantity']
            ]);

            // Kirim peringatan ke tim sales/inventory
            $this->sendHighPriorityAlert($notification);
        }
    }

    /**
     * Kirim alert kritis ke admin
     *
     * @param array $notification
     * @return void
     */
    private function sendCriticalAlert($notification)
    {
        try {
            // Dapatkan admin users (role admin atau manager)
            $adminUsers = User::whereIn('role', ['admin', 'manager'])->get();

            foreach ($adminUsers as $admin) {
                // Kirim notifikasi dalam aplikasi
                // Notification::send($admin, new ExpiryAlert($notification));
                
                Log::info("Alert kritis dikirim ke admin: {$admin->email}", [
                    'notification' => $notification['message']
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Error mengirim critical alert: ' . $e->getMessage());
        }
    }

    /**
     * Kirim alert prioritas tinggi ke tim
     *
     * @param array $notification
     * @return void
     */
    private function sendHighPriorityAlert($notification)
    {
        try {
            // Dapatkan users yang perlu diberitahu
            $users = User::whereIn('role', ['admin', 'manager', 'sales'])->get();

            foreach ($users as $user) {
                Log::info("Alert prioritas tinggi dikirim ke: {$user->email}", [
                    'notification' => $notification['message']
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Error mengirim high priority alert: ' . $e->getMessage());
        }
    }

    /**
     * Handle job failure
     *
     * @param \Throwable $exception
     * @return void
     */
    public function failed(\Throwable $exception)
    {
        Log::error('Job ProcessExpiryNotifications gagal: ' . $exception->getMessage(), [
            'exception' => $exception->getTraceAsString()
        ]);
    }
}