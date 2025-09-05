<?php

declare(strict_types=1);

namespace UseCases\Classification;

use App\Models\Ticket;
use Classification\Contracts\ClassifierInterface;
use Classification\Services\TicketClassifier;
use Ticket\Contracts\Services\TicketServiceInterface;
use Ticket\Services\TicketService;
use UseCases\DomainServiceFactory;

class ClassifyTicket
{
    public function __construct(
        private DomainServiceFactory $domainServiceFactory,
    ) {}

    /**
     * Classify a ticket and update it with classification results
     */
    public function execute(Ticket $ticket): void
    {
        /** @var TicketClassifier $ticketService */
        $ticketService = $this->domainServiceFactory->create(ClassifierInterface::class);
        $classification = $ticketService->classify($ticket);

        /** @var TicketService $ticketService */
        $ticketService = $this->domainServiceFactory->create(TicketServiceInterface::class);
        $ticketService->classify($classification, $ticket);
    }
}
