<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NotificationService;

class ProcessNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:process {--type=all : Type of notifications to process (all, due, overdue)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process and send notifications for due and overdue loans';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->option('type');
        $notificationService = new NotificationService();

        $this->info('Starting notification processing...');

        switch ($type) {
            case 'due':
                $this->info('Processing due loan notifications...');
                $notificationService->processDueLoans();
                $this->info('Due loan notifications processed successfully!');
                break;

            case 'overdue':
                $this->info('Processing overdue loan notifications...');
                $notificationService->processOverdueLoans();
                $this->info('Overdue loan notifications processed successfully!');
                break;

            case 'all':
            default:
                $this->info('Processing all loan notifications...');
                $notificationService->processDueLoans();
                $notificationService->processOverdueLoans();
                $this->info('All loan notifications processed successfully!');
                break;
        }

        $this->info('Notification processing completed!');
    }
}