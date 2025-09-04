<?php

declare(strict_types=1);

namespace Classification\Contracts;

use App\Models\Ticket;
use Classification\ValueObjects\ClassificationResult;

interface ClassifierInterface
{
    /**
     * Classify a ticket and return classification result
     */
    public function classify(Ticket $ticket): ClassificationResult;
}
