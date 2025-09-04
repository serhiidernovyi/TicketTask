<?php

declare(strict_types=1);

namespace Ticket\Contracts\Services;

interface StatsServiceInterface
{
    public function getAllStats(): array;
}
