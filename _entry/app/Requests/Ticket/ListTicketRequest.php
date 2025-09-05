<?php

declare(strict_types=1);

namespace App\Requests\Ticket;

use Ticket\Contracts\Requests\ListInterface;
use Illuminate\Foundation\Http\FormRequest;

class ListTicketRequest extends FormRequest implements ListInterface
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'search' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:new,open,pending,closed',
            'category' => 'nullable|string|max:255',
            'sort_created_at' => 'nullable|string|sometimes:ASC,DESC',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100'
        ];
    }


    public function getPerPage(): int
    {
        return (int)$this->input('per_page', 10);
    }

    public function getPage(): int
    {
        return (int)$this->input('page');
    }
}
