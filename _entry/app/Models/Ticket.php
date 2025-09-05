<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\Filterable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property-read string $id
 * @property string $subject
 * @property string $body
 * @property string $status   new|open|pending|closed
 * @property string|null $category
 * @property string|null $explanation
 * @property float|null $confidence   0.0..1.0
 * @property string|null $note
 * @property bool $category_is_manual
 * @property Carbon|null $category_changed_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @method static find(string $ticketId)
 */
class Ticket extends Model
{
    use HasUlids, HasFactory, Filterable;

    public const TICKET = Ticket::class;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'subject',
        'body',
        'status',
        'category',
        'explanation',
        'confidence',
        'note',
        'category_is_manual',
        'category_changed_at',
    ];

    protected $casts = [
        'confidence' => 'float',
        'category_is_manual' => 'boolean',
        'category_changed_at' => 'datetime',
    ];
}
