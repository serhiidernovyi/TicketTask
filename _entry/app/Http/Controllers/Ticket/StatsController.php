<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use UseCases\Ticket\Stats as StatsUseCase;

class StatsController extends Controller
{
    public function __construct(
        private readonly StatsUseCase $statsUseCase,
    ) {}

    /**
     * Get dashboard statistics
     */
    public function index(): JsonResponse
    {
        $stats = $this->statsUseCase->getAllStats();

        return response()->json($stats);
    }
}
