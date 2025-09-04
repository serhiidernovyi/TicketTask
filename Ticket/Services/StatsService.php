<?php

declare(strict_types=1);

namespace Ticket\Services;

use Ticket\Contracts\Services\StatsServiceInterface;
use Ticket\Entities\Ticket;

class StatsService implements StatsServiceInterface
{
    public function __construct(
        private readonly Ticket $ticket
    ){}

    /**
     * Get all statistics combined
     */
    public function getAllStats(): array
    {
        return [
            'categories' => $this->ticket->getCategoryStats(),
            'statuses' => $this->ticket->getStatusStats(),
            'daily_stats' => $this->ticket->getDailyStats(),
            'overview' => $this->ticket->getOverviewStats(),
            'top_issues' => $this->ticket->getTopIssues(),
        ];
    }
}