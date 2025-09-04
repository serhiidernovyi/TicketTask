<?php

declare(strict_types=1);

namespace App\DTO\Ticket;

use App\Requests\Ticket\CreateTicketRequest;
use App\Requests\Ticket\UpdateTicketRequest;

class TicketDTO
{
    public function __construct(
        public readonly string $subject,
        public readonly string $body,
        public readonly string $status = 'open',
        public readonly string|null $category,
        public readonly string|null $note,
    ) {}

    public static function fromRequest(CreateTicketRequest|UpdateTicketRequest $request): self
    {
        return new self(
            subject: $request->validated('subject') ?? '',
            body: $request->validated('body') ?? '',
            status: $request->validated('status', 'open'),
            category: $request->validated('category'),
            note: $request->validated('note')
        );
    }
}
