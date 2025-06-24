<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();
// Schedule untuk cleanup draft expired
Schedule::command('drafts:cleanup')
    ->daily()
    ->at('01:00')
    ->description('Cleanup expired draft sales')
    ->appendOutputTo(storage_path('logs/drafts-cleanup.log'));

// Schedule untuk notifikasi draft yang akan expire
Schedule::command('drafts:notify-expiring')
    ->daily()
    ->at('08:00')
    ->description('Notify about drafts expiring soon')
    ->appendOutputTo(storage_path('logs/drafts-notification.log'));

// Optional: Command untuk manual cleanup dengan konfirmasi
Artisan::command('drafts:cleanup-manual', function () {
    if ($this->confirm('Are you sure you want to cleanup expired drafts manually?')) {
        $this->call('drafts:cleanup');
    }
})->purpose('Manually cleanup expired drafts with confirmation');

// Optional: Command untuk manual check expiring drafts
Artisan::command('drafts:check-expiring', function () {
    $this->call('drafts:notify-expiring');
})->purpose('Manually check for expiring drafts');
