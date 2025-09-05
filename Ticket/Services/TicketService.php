<?php

declare(strict_types=1);

namespace Ticket\Services;

use App\DTO\Ticket\TicketDTO;
use App\Models\Ticket as TicketModel;
use Classification\ValueObjects\ClassificationResult;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Ticket\Contracts\Requests\ListInterface;
use Ticket\Contracts\Services\TicketServiceInterface;
use Ticket\Contracts\Entities\TicketInterface;
use Illuminate\Foundation\Application;
use Ticket\Entities\Ticket;
use Ticket\Filters\TicketFilter;

class TicketService implements TicketServiceInterface
{
    public function __construct(
        private Application $app,
        private Ticket $ticket
    ){}

    public function create(TicketDTO $ticketDto): TicketInterface
    {
        /** @var Ticket $ticket */
        $ticket = Ticket::create([
            'subject' => $ticketDto->subject,
            'body' => $ticketDto->body,
            'status' => $ticketDto->status,
            'category' => $ticketDto->category,
            'note' => $ticketDto->note,
        ]);

        return $ticket;
    }

    public function getById(string $id): TicketInterface
    {
        return Ticket::findOrFail($id);
    }

    public function update(TicketDTO $ticketDto, \App\Models\Ticket $ticket): TicketInterface
    {
        $updateData = [
            'subject' => $ticketDto->subject,
            'body' => $ticketDto->body,
            'status' => $ticketDto->status,
            'note' => $ticketDto->note,
        ];

        if ($ticketDto->category !== null && $ticketDto->category !== $ticket->category) {
            $updateData['category'] = $ticketDto->category;
            $updateData['category_is_manual'] = true;
            $updateData['category_changed_at'] = now();
        } elseif ($ticketDto->category !== null) {
            $updateData['category'] = $ticketDto->category;
        }

        $ticket->update($updateData);

        return $this->getById($ticket->id);
    }

    /**
     * @throws BindingResolutionException
     */
    public function list(ListInterface $request): ?LengthAwarePaginator
    {
        $data = $request->validated();
        $filter = $this->app->make(TicketFilter::class, ['queryParams' => array_filter($data)]);
        $query = $this->ticket->filter($filter);

        return $query->paginate($request->getPerPage());
    }

    public function classify(ClassificationResult $classification, TicketModel $ticket, bool $force = false): void
    {
        $updateData = [
            'explanation' => $classification->explanation,
            'confidence' => $classification->confidence,
            'category_changed_at' => now(),
        ];

        if (!$ticket->category_is_manual || $force) {
            $updateData['category'] = $classification->category;
            $updateData['category_is_manual'] = false;
        }

        $ticket->update($updateData);
    }
}