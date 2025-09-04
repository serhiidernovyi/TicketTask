<?php

declare(strict_types=1);

namespace App\Resources\Ticket;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Ticket\Contracts\Entities\TicketInterface;

class TicketResource extends JsonResource
{
    public static $wrap = null;
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        /** @var TicketInterface $this */
        return [
            'id' => $this->getId(),
            'subject' => $this->getSubject(),
            'body' => $this->getBody(),
            'status' => $this->getStatus(),
            'category' => $this->getCategory(),
            'confidence' => $this->getConfidence(),
            'explanation' => $this->getExplanation(),
            'note' => $this->getNote(),
            'category_is_manual' => $this->isCategoryManuallySet(),
            'category_changed_at' => $this->getCategoryChangedAt()?->toISOString(),
            'created_at' => $this->getCreatedAt()?->toISOString(),
            'updated_at' => $this->getUpdatedAt()?->toISOString(),
        ];
    }
}
