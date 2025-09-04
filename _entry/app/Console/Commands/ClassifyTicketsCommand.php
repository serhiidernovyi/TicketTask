<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\ClassifyTicket;
use App\Models\Ticket;
use Illuminate\Console\Command;

class ClassifyTicketsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tickets:bulk-classify 
                            {--unclassified : Only classify tickets without category}
                            {--all : Classify all tickets}
                            {--force : Force reclassification even if already classified}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Classify tickets using AI/ML';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $query = Ticket::query();

        if ($this->option('unclassified')) {
            $query->whereNull('category');
            $this->info('Classifying unclassified tickets...');
        } elseif ($this->option('all')) {
            $this->info('Classifying all tickets...');
        } else {
            $this->error('Please specify --unclassified or --all option');
            return 1;
        }

        if (!$this->option('force')) {
            $query->where('category_is_manual', false);
        }

        $tickets = $query->get();

        if ($tickets->isEmpty()) {
            $this->info('No tickets found for classification');
            return 0;
        }

        $this->info("Found {$tickets->count()} tickets to classify");

        $bar = $this->output->createProgressBar($tickets->count());
        $bar->start();

        foreach ($tickets as $ticket) {
            ClassifyTicket::dispatch($ticket->id);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Dispatched {$tickets->count()} classification jobs");

        return 0;
    }
}
