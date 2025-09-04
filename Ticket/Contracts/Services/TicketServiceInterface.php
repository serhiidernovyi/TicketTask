<?php

declare(strict_types=1);

namespace Ticket\Contracts\Services;

use App\DTO\Ticket\TicketDTO;
use App\Models\Ticket;
use App\Requests\Ticket\ListTicketRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Ticket\Contracts\Entities\TicketInterface;
use Ticket\Contracts\Requests\ListInterface;

interface TicketServiceInterface
{
    public function create(TicketDTO $ticketDto): TicketInterface;
    public function getById(string $id): TicketInterface;
    public function update(TicketDTO $ticketDto, Ticket $ticket): TicketInterface;
    public function list(ListInterface $request): ?LengthAwarePaginator;
}