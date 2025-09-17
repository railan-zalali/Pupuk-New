<?php

namespace App\View\Components;

use App\Services\ExpiryNotificationService;
use Illuminate\View\Component;

class ExpiryNotificationWidget extends Component
{
    public $summary;
    public $topNotifications;
    public $error;

    protected $expiryService;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->expiryService = app(ExpiryNotificationService::class);
        
        try {
            $this->summary = $this->expiryService->getNotificationSummary();
            $criticalNotifications = $this->expiryService->getNotificationsBySeverity('critical');
            $highNotifications = $this->expiryService->getNotificationsBySeverity('high');
            
            // Ambil 5 notifikasi terpenting untuk ditampilkan
            $this->topNotifications = array_merge(
                array_slice($criticalNotifications, 0, 3),
                array_slice($highNotifications, 0, 2)
            );

        } catch (\Exception $e) {
            $this->summary = [
                'total' => 0,
                'critical' => 0,
                'high' => 0,
                'medium' => 0
            ];
            $this->topNotifications = [];
            $this->error = $e->getMessage();
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.expiry-notification-widget');
    }
}