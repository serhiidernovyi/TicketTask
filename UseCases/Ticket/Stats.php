<?php

declare(strict_types=1);

namespace UseCases\Ticket;

use Ticket\Contracts\Services\StatsServiceInterface;
use UseCases\DomainServiceFactory;

class Stats
{
    public function __construct(
        private readonly DomainServiceFactory $domainServiceFactory,
    ) {}

    /**
     * Get all dashboard statistics
     */
    public function getAllStats(): array
    {
        /** @var StatsServiceInterface $statsService */
        $statsService = $this->domainServiceFactory->create(StatsServiceInterface::class);
        
        return $statsService->getAllStats();
    }
}