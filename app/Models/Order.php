<?php

namespace App\Models;

use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'user_id',
    'status',
    'payment_provider',
    'payment_reference',
    'currency',
    'total_cents',
    'customer_name',
    'customer_email',
    'phone',
    'shipping_address',
    'notes',
])]

class Order extends Model
{
    /** @var array<string, list<string>> */
    private const STATUS_TRANSITIONS = [
        'pending_payment' => ['paid', 'cancelled'],
        'paid' => ['processing', 'cancelled'],
        'processing' => ['shipped', 'cancelled'],
        'shipped' => ['delivered'],
        'delivered' => [],
        'cancelled' => [],
    ];

    /** @use HasFactory<OrderFactory> */
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function canTransitionTo(string $status): bool
    {
        return in_array($status, self::STATUS_TRANSITIONS[$this->status] ?? [], true);
    }

    /** @return list<string> */
    public function availableStatuses(): array
    {
        return [$this->status, ...(self::STATUS_TRANSITIONS[$this->status] ?? [])];
    }
}
