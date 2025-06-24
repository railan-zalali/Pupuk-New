<?php

namespace App\Console\Commands;

use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class NotifyExpiringDrafts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'drafts:notify-expiring';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify about drafts that will expire in 3 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for expiring drafts...');

        // Get drafts that will expire in 3 days
        $expiringDrafts = Sale::where('status', 'draft')
            ->whereBetween('created_at', [
                Carbon::now()->subDays(30),
                Carbon::now()->subDays(27)
            ])
            ->with('customer', 'user')
            ->get();

        $count = $expiringDrafts->count();

        if ($count == 0) {
            $this->info('No drafts expiring soon.');
            return Command::SUCCESS;
        }

        $this->warn("Found {$count} draft(s) expiring in the next 3 days:");

        // Create notification summary
        $summary = [];

        foreach ($expiringDrafts as $draft) {
            $daysRemaining = 30 - Carbon::parse($draft->created_at)->diffInDays(now());

            $this->line(sprintf(
                "- %s | Customer: %s | Amount: Rp %s | Expires in %d day(s)",
                $draft->invoice_number,
                $draft->customer ? $draft->customer->nama : 'Guest',
                number_format($draft->total_amount, 0, ',', '.'),
                $daysRemaining
            ));

            // Log for record keeping
            Log::warning('Draft expiring soon', [
                'draft_id' => $draft->id,
                'invoice_number' => $draft->invoice_number,
                'customer' => $draft->customer ? $draft->customer->nama : 'Guest',
                'total_amount' => $draft->total_amount,
                'days_remaining' => $daysRemaining,
                'created_by' => $draft->user->name
            ]);
        }

        // TODO: Here you can add email notification logic
        // Example:
        // Mail::to(config('mail.admin_email'))->send(new ExpiringDraftNotification($expiringDrafts));

        $this->newLine();
        $this->info("Notification complete for {$count} expiring draft(s).");

        return Command::SUCCESS;
    }
}
