<?php

namespace Ticket\Contracts\Entities;

interface TicketInterface
{
    public function getId(): string;
    public function getSubject(): string;
    public function getBody(): string;
    public function getStatus(): string;
    public function getCategory(): ?string;
    public function getExplanation(): ?string;
    public function getConfidence(): ?float;
    public function getNote(): ?string;
    public function isCategoryManuallySet(): ?bool;
    public function getCreatedAt(): ?\DateTimeInterface;
    public function getUpdatedAt(): ?\DateTimeInterface;
    public function getConfidencePercentage(): ?int;
}