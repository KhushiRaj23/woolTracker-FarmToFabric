<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shipment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'distributor_id',
        'order_id',
        'tracking_number',
        'carrier',
        'status',
        'estimated_delivery_date',
        'shipping_method',
        'shipping_cost',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'estimated_delivery_date' => 'datetime',
        'shipping_cost' => 'decimal:2',
    ];

    /**
     * Get the order associated with the shipment.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the distributor that owns the shipment.
     */
    public function distributor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'distributor_id');
    }

    /**
     * Check if the shipment can be edited.
     */
    public function isEditable(): bool
    {
        return in_array($this->status, ['pending', 'in_transit']);
    }

    /**
     * Check if the shipment can be deleted.
     */
    public function isDeletable(): bool
    {
        return $this->status === 'pending';
    }
}
