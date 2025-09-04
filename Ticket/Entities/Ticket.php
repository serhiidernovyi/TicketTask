<?php

namespace Ticket\Entities;

use Illuminate\Support\Facades\DB;
use Ticket\Contracts\Entities\TicketInterface;
use App\Models\Ticket as BaseModel;

/**
 * @method filter(mixed $filter)
 * @method static create(array $array)
 * @method static select($raw, $raw1)
 * @method static count()
 * @method static whereNull(string $string)
 * @method static where(string $string, true $true)
 * @method static whereNotNull(string $string)
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

    /**
     * Get statistics by categories
     */
    public static function getCategoryStats(): array
    {
        return self::select('category', DB::raw('count(*) as count'))
            ->whereNotNull('category')
            ->groupBy('category')
            ->pluck('count', 'category')
            ->toArray();
    }

    /**
     * Get statistics by statuses
     */
    public static function getStatusStats(): array
    {
        return self::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }

    /**
     * Get daily statistics for specified number of days
     */
    public static function getDailyStats(int $days = 30): array
    {
        $startDate = now()->subDays($days);

        $created = self::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('count(*) as created')
        )
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->pluck('created', 'date')
            ->toArray();

        $closed = self::select(
            DB::raw('DATE(updated_at) as date'),
            DB::raw('count(*) as closed')
        )
            ->where('status', 'closed')
            ->where('updated_at', '>=', $startDate)
            ->groupBy('date')
            ->pluck('closed', 'date')
            ->toArray();

        $dailyStats = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dailyStats[] = [
                'date' => $date,
                'created' => $created[$date] ?? 0,
                'closed' => $closed[$date] ?? 0,
            ];
        }

        return $dailyStats;
    }

    /**
     * Get overview statistics
     */
    public static function getOverviewStats(): array
    {
        $totalTickets = self::count();
        $unclassified = self::whereNull('category')->count();
        $manualClassifications = self::where('category_is_manual', true)->count();
        $aiClassifications = self::where('category_is_manual', false)
            ->whereNotNull('category')
            ->count();

        $avgConfidence = self::whereNotNull('confidence')
            ->avg('confidence') ?? 0;

        return [
            'total_tickets' => $totalTickets,
            'unclassified' => $unclassified,
            'manual_classifications' => $manualClassifications,
            'ai_classifications' => $aiClassifications,
            'avg_confidence' => round($avgConfidence, 2),
        ];
    }

    /**
     * Get top issues by category
     */
    public static function getTopIssues(int $limit = 5): array
    {
        $totalTickets = self::whereNotNull('category')->count();

        if ($totalTickets === 0) {
            return [];
        }

        return self::select('category', DB::raw('count(*) as count'))
            ->whereNotNull('category')
            ->groupBy('category')
            ->orderByDesc('count')
            ->limit($limit)
            ->get()
            ->map(function ($item) use ($totalTickets) {
                return [
                    'category' => $item->category,
                    'count' => $item->count,
                    'percentage' => round(($item->count / $totalTickets) * 100, 1),
                ];
            })
            ->toArray();
    }
}