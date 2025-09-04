<?php

declare(strict_types=1);

namespace App\Requests\Ticket;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subject' => 'sometimes|string|max:255',
            'body' => 'sometimes|string',
            'status' => 'sometimes|in:open,new,pending,closed',
            'category' => 'sometimes|string|max:255',
            'note' => 'sometimes|string',
        ];
    }
}
