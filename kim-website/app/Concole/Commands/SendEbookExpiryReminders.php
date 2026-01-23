<?php

namespace App\Console\Commands;

use App\Services\EbookAccessService;
use Illuminate\Console\Command;

class SendEbookExpiryReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ebook:send-expiry-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder emails for ebook accesses expiring in 7 days';

    protected $ebookService;

    public function __construct(EbookAccessService $ebookService)
    {
        parent::__construct();
        $this->ebookService = $ebookService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Sending e-book expiry reminders...');

        $this->ebookService->sendExpiryReminders();

        $this->info('Reminder emails sent successfully!');
        
        return Command::SUCCESS;
    }
}