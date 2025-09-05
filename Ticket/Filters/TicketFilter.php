<?php

declare(strict_types=1);

namespace Ticket\Filters;

use App\Contracts\AbstractFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class TicketFilter extends AbstractFilter
{
    public const SEARCH = 'search';
    public const STATUS = 'status';
    public const CREATED_AT = 'created_at';
    public const SORT_CREATED_AT = 'sort_created_at';

    protected function getCallbacks(): array
    {
        return [
            self::SEARCH => [$this, 'search'],
            self::STATUS => [$this, 'status'],
            self::CREATED_AT => [$this, 'createdAt'],
            self::SORT_CREATED_AT => [$this, 'sortCreatedAt'],

        ];
    }

    public function search(Builder $builder, $value): void
    {
        $builder->where('subject', 'like', "%$value%")
            ->orWhere('body', 'like', "%$value%");
    }

    public function createdAt(Builder $builder, string $value): void
    {
        $date = Carbon::parse($value);

        $builder->whereBetween('created_at', [
            $date->copy()->startOfDay(),
            $date->copy()->endOfDay(),
        ]);
    }

    public function sortCreatedAt(Builder $builder, $value): void
    {
        $builder->orderBy('created_at', $value);
    }

    public function status(Builder $builder, $value): void
    {
        $builder->where('status', '=', $value);
    }
}