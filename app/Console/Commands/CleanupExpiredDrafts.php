<?php

namespace App\Console\Commands;

use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class CleanupExpiredDrafts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'drafts:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup expired draft sales (older than 30 days)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting cleanup of expired drafts...');

        // Get expired drafts
        $expiredDrafts = Sale::where('status', 'draft')
            ->where('created_at', '<=', Carbon::now()->subDays(30))
            ->get();

        $count = $expiredDrafts->count();

        if ($count == 0) {
            $this->info('No expired drafts found.');
            return Command::SUCCESS;
        }

        $this->info("Found {$count} expired draft(s). Cleaning up...");

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        foreach ($expiredDrafts as $draft) {
            // Log draft details before deletion
            Log::info('Deleting expired draft', [
                'draft_id' => $draft->id,
                'invoice_number' => $draft->invoice_number,
                'created_at' => $draft->created_at,
                'customer' => $draft->customer ? $draft->customer->nama : 'Guest',
                'total_amount' => $draft->total_amount
            ]);

            // Delete related sale details first
            $draft->saleDetails()->delete();

            // Force delete the draft (bypass soft delete)
            $draft->forceDelete();

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        // Clear related caches
        Cache::forget('draft_sales_page_1');
        Cache::forget('completed_sales_page_1');

        $this->info("Successfully cleaned up {$count} expired draft(s).");
        Log::info("Draft cleanup completed: {$count} expired drafts removed.");

        return Command::SUCCESS;
    }
}
