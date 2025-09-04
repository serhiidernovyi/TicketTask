<?php

namespace UseCases\Ticket;

use App\DTO\Ticket\TicketDTO;
use App\Requests\Ticket\ListTicketRequest;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Ticket\Contracts\Services\TicketServiceInterface;
use Ticket\Services\TicketService;
use Ticket\Contracts\Entities\TicketInterface;
use UseCases\DomainServiceFactory;

class Ticket
{
    public function __construct(
        private DomainServiceFactory $domainServiceFactory,
    ) {
    }
    /**
     * @throws \Throwable
     */
    public function create(TicketDTO $ticketDto): TicketInterface
    {
        /** @var TicketService $ticketService */
        $ticketService = $this->domainServiceFactory->create(TicketServiceInterface::class);
        return $ticketService->create($ticketDto);
    }

    public function getById(string $id): TicketInterface
    {
        /** @var TicketService $ticketService */
        $ticketService = $this->domainServiceFactory->create(TicketServiceInterface::class);
        return $ticketService->getById($id);
    }

    /**
     * @throws \Throwable
     */
    public function update(TicketDTO $ticketDto, \App\Models\Ticket $ticket): TicketInterface
    {
        /** @var TicketService $ticketService */
        $ticketService = $this->domainServiceFactory->create(TicketServiceInterface::class);
        return $ticketService->update($ticketDto, $ticket);
    }

    /**
     * @throws BindingResolutionException
     */
    public function list(ListTicketRequest $request): ?LengthAwarePaginator
    {
        /** @var TicketService $ticketService */
        $ticketService = $this->domainServiceFactory->create(TicketServiceInterface::class);
        return $ticketService->list($request);
    }
}