<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Ticket;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use UseCases\Classification\ClassifyTicket as ClassifyTicketUseCase;

class ClassifyTicket implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public readonly string $ticketId
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(ClassifyTicketUseCase $classifyTicketUseCase): void
    {
        /** @var \Ticket\Entities\Ticket $ticket */
        $ticket = Ticket::find($this->ticketId);

        if (!$ticket) {
            Log::warning("Ticket {$this->ticketId} not found for classification");
            return;
        }

        try {
            $classifyTicketUseCase->execute($ticket);
            Log::info("Ticket {$this->ticketId} classified successfully");
        } catch (\Exception $e) {
            Log::error("Failed to classify ticket {$this->ticketId}: " . $e->getMessage());
            
            // If rate limit error, retry later
            if (str_contains($e->getMessage(), 'rate limit')) {
                Log::warning("Rate limit hit for ticket {$this->ticketId}, retrying in 60 seconds");
                $this->release(60); // Retry in 60 seconds
                return;
            }
            
            throw $e;
        }
    }

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The maximum number of seconds the job can run.
     */
    public int $timeout = 120;
}
