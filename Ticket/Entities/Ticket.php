<?php

namespace Ticket\Entities;

use Ticket\Contracts\Entities\TicketInterface;
use App\Models\Ticket as BaseModel;

/**
 * @method filter(mixed $filter)
 * @method static create(array $array)
 **/
class Ticket extends BaseModel implements TicketInterface
{
    public function getId(): string
    {
        return $this->id;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function getExplanation(): ?string
    {
        return $this->explanation;
    }

    public function getConfidence(): ?float
    {
        return $this->confidence;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function isCategoryManuallySet(): ?bool
    {
        return $this->category_is_manual;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function getConfidencePercentage(): ?int
    {
        return $this->confidence ? (int) round($this->confidence * 100) : null;
    }

    public function getCategoryChangedAt(): ?\DateTimeInterface
    {
        return $this->category_changed_at;
    }
}