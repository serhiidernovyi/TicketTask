<?php

declare(strict_types=1);

namespace App\Requests\Ticket;

use Illuminate\Foundation\Http\FormRequest;

class ClassifyTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // For now, allow all users to classify tickets
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // No specific validation rules needed for classification
            // The classification is based on the ticket content, not request data
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            // No custom messages needed for now
        ];
    }
}
