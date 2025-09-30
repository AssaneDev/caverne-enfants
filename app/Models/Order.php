<?php

namespace App\Models;

use App\Observers\OrderObserver;
use App\OrderStatus;
use App\PaymentMethod;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasUlids;

    protected $fillable = [
        'order_number',
        'user_id',
        'status',
        'subtotal_cents',
        'shipping_cents',
        'tax_cents',
        'total_cents',
        'currency',
        'payment_method',
        'payment_reference',
        'payment_status',
        'billing_name',
        'billing_email',
        'billing_address',
        'billing_city',
        'billing_postal_code',
        'billing_country',
        'ship_first_name',
        'ship_last_name',
        'ship_line1',
        'ship_line2',
        'ship_city',
        'ship_state',
        'ship_postcode',
        'ship_country',
        'tracking_carrier',
        'tracking_number',
        'shipped_at',
        'delivered_at',
        'paid_at',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
        'payment_method' => PaymentMethod::class,
        'subtotal_cents' => 'integer',
        'shipping_cents' => 'integer',
        'tax_cents' => 'integer',
        'total_cents' => 'integer',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::observe(OrderObserver::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function getTotalAttribute(): float
    {
        return $this->total_cents / 100;
    }

    public function getSubtotalAttribute(): float
    {
        return $this->subtotal_cents / 100;
    }

    public function getShippingAttribute(): float
    {
        return $this->shipping_cents / 100;
    }
}
