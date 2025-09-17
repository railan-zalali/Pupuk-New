<?php

namespace App\Http\Controllers;

use App\Services\ExpiryNotificationService;
use App\Services\FifoService;
use App\Jobs\ProcessExpiryNotifications;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ExpiryNotificationController extends Controller
{
    protected $expiryService;
    protected $fifoService;

    public function __construct(ExpiryNotificationService $expiryService, FifoService $fifoService)
    {
        $this->expiryService = $expiryService;
        $this->fifoService = $fifoService;
    }

    /**
     * Mendapatkan semua notifikasi kedaluwarsa
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $notifications = $this->expiryService->getExpiryNotifications();
            $summary = $this->expiryService->getNotificationSummary();

            // Filter berdasarkan severity jika diminta
            if ($request->has('severity')) {
                $notifications = $this->expiryService->getNotificationsBySeverity($request->severity);
            }

            // Filter berdasarkan type jika diminta
            if ($request->has('type')) {
                $notifications = array_filter($notifications, function ($notification) use ($request) {
                    return $notification['type'] === $request->type;
                });
            }

            // Pagination manual jika diperlukan
            $page = $request->get('page', 1);
            $perPage = $request->get('per_page', 10);
            $offset = ($page - 1) * $perPage;
            
            $paginatedNotifications = array_slice($notifications, $offset, $perPage);

            return response()->json([
                'success' => true,
                'data' => [
                    'notifications' => array_values($paginatedNotifications),
                    'summary' => $summary,
                    'pagination' => [
                        'current_page' => $page,
                        'per_page' => $perPage,
                        'total' => count($notifications),
                        'total_pages' => ceil(count($notifications) / $perPage)
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil notifikasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mendapatkan ringkasan notifikasi untuk dashboard
     *
     * @return JsonResponse
     */
    public function summary(): JsonResponse
    {
        try {
            $summary = $this->expiryService->getNotificationSummary();
            $statistics = $this->fifoService->getExpiryStatistics();

            return response()->json([
                'success' => true,
                'data' => [
                    'notifications' => $summary,
                    'statistics' => $statistics
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil ringkasan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mendapatkan notifikasi berdasarkan tingkat keparahan
     *
     * @param string $severity
     * @return JsonResponse
     */
    public function getBySeverity($severity): JsonResponse
    {
        try {
            $validSeverities = ['critical', 'high', 'medium'];
            
            if (!in_array($severity, $validSeverities)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tingkat keparahan tidak valid'
                ], 400);
            }

            $notifications = $this->expiryService->getNotificationsBySeverity($severity);

            return response()->json([
                'success' => true,
                'data' => array_values($notifications)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil notifikasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mendapatkan notifikasi untuk produk tertentu
     *
     * @param int $productId
     * @return JsonResponse
     */
    public function getByProduct($productId): JsonResponse
    {
        try {
            $notifications = $this->expiryService->getNotificationsForProduct($productId);

            return response()->json([
                'success' => true,
                'data' => array_values($notifications)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil notifikasi produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mendapatkan rekomendasi aksi untuk batch
     *
     * @param int $batchId
     * @return JsonResponse
     */
    public function getActionRecommendations($batchId): JsonResponse
    {
        try {
            $recommendations = $this->expiryService->getActionRecommendations($batchId);

            if (empty($recommendations)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Batch tidak ditemukan atau tidak memiliki tanggal kedaluwarsa'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $recommendations
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil rekomendasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menandai batch sebagai sudah ditangani
     *
     * @param Request $request
     * @param int $batchId
     * @return JsonResponse
     */
    public function markHandled(Request $request, $batchId): JsonResponse
    {
        try {
            $action = $request->input('action', 'handled');
            
            $this->expiryService->markBatchHandled($batchId, $action);

            return response()->json([
                'success' => true,
                'message' => 'Batch berhasil ditandai sebagai sudah ditangani'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menandai batch: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Refresh cache notifikasi
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        try {
            $this->expiryService->clearNotificationCache();
            
            // Jalankan job untuk memproses notifikasi
            ProcessExpiryNotifications::dispatch();

            return response()->json([
                'success' => true,
                'message' => 'Cache notifikasi berhasil di-refresh'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal me-refresh notifikasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mendapatkan widget dashboard untuk notifikasi kedaluwarsa
     *
     * @return \Illuminate\View\View
     */
    public function dashboardWidget()
    {
        try {
            $summary = $this->expiryService->getNotificationSummary();
            $criticalNotifications = $this->expiryService->getNotificationsBySeverity('critical');
            $highNotifications = $this->expiryService->getNotificationsBySeverity('high');
            
            // Ambil 5 notifikasi terpenting untuk ditampilkan
            $topNotifications = array_merge(
                array_slice($criticalNotifications, 0, 3),
                array_slice($highNotifications, 0, 2)
            );

            return view('components.expiry-notification-widget', compact(
                'summary',
                'topNotifications'
            ));

        } catch (\Exception $e) {
            return view('components.expiry-notification-widget', [
                'summary' => [
                    'total' => 0,
                    'critical' => 0,
                    'high' => 0,
                    'medium' => 0
                ],
                'topNotifications' => [],
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Halaman detail notifikasi kedaluwarsa
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function show(Request $request)
    {
        try {
            $notifications = $this->expiryService->getExpiryNotifications();
            $summary = $this->expiryService->getNotificationSummary();
            $statistics = $this->fifoService->getExpiryStatistics();

            // Filter berdasarkan parameter
            $severity = $request->get('severity');
            $type = $request->get('type');

            if ($severity) {
                $notifications = $this->expiryService->getNotificationsBySeverity($severity);
            }

            if ($type) {
                $notifications = array_filter($notifications, function ($notification) use ($type) {
                    return $notification['type'] === $type;
                });
            }

            return view('expiry-notifications.index', compact(
                'notifications',
                'summary',
                'statistics',
                'severity',
                'type'
            ));

        } catch (\Exception $e) {
            return view('expiry-notifications.index', [
                'notifications' => [],
                'summary' => [],
                'statistics' => [],
                'error' => $e->getMessage()
            ]);
        }
    }
}