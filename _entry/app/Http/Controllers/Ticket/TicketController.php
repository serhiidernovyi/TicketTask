<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ticket;

use App\DTO\Ticket\TicketDTO;
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Requests\Ticket\ListTicketRequest;
use App\Resources\Ticket\TicketListResource;
use App\Resources\Ticket\TicketResource;
use App\Requests\Ticket\CreateTicketRequest;
use App\Requests\Ticket\UpdateTicketRequest;
use App\Requests\Ticket\ClassifyTicketRequest;
use UseCases\Ticket\Ticket as TicketUseCase;
use Symfony\Component\HttpFoundation\Response;

class TicketController extends Controller
{
    public function __construct(
        private readonly TicketUseCase $ticketUseCase,
    ) {}

    /**
     * @throws \Throwable
     */
    public function store(CreateTicketRequest $request): Response
    {
        $ticketDto = TicketDTO::fromRequest($request);
        $ticket = $this->ticketUseCase->create($ticketDto);
        $resource = new TicketResource($ticket);

        return $resource->response()->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @throws \Throwable
     */
    public function show(Ticket $ticket): Response
    {
        $ticket = $this->ticketUseCase->getById($ticket->id);
        $resource = new TicketResource($ticket);

        return $resource->response();
    }

    /**
     * @throws \Throwable
     */
    public function update(Ticket $ticket, UpdateTicketRequest $request): Response
    {
        $ticketDto = TicketDTO::fromRequest($request);
        $updatedTicket = $this->ticketUseCase->update($ticketDto, $ticket);
        $resource = new TicketResource($updatedTicket);

        return $resource->response()->setStatusCode(Response::HTTP_OK);
    }

    /**
     * @throws \Throwable
     */
    public function index(ListTicketRequest $request): Response
    {
        $response = $this->ticketUseCase->list($request);
        $resource = new TicketListResource($response);

        return $resource->response()->setStatusCode(Response::HTTP_OK);
    }
}
