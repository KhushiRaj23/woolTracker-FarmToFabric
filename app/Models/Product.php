<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'distributor_id',
        'name',
        'description',
        'sku',
        'price',
        'stock_quantity',
        'category',
        'status',
        'batch_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'stock_quantity' => 'integer',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::deleting(function ($product) {
            if ($product->orderItems()->exists()) {
                throw new \Exception('Cannot delete product with existing orders.');
            }
        });
    }

    /**
     * Get the distributor that owns the product.
     */
    public function distributor(): BelongsTo
    {
        return $this->belongsTo(Distributor::class);
    }

    /**
     * Get the order items for the product.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Scope a query to only include available products.
     */
    public function scopeAvailable(Builder $query): void
    {
        $query->where('status', 'active')
              ->where('stock_quantity', '>', 0);
    }

    /**
     * Check if the product is available.
     */
    public function isAvailable(): bool
    {
        return $this->status === 'active' && $this->stock_quantity > 0;
    }

    /**
     * Check if the product is in stock.
     */
    public function inStock(): bool
    {
        return $this->stock_quantity > 0;
    }

    /**
     * Update stock quantity
     */
    public function updateStock(int $quantity): bool
    {
        if ($this->stock_quantity + $quantity < 0) {
            return false;
        }

        $this->stock_quantity += $quantity;
        return $this->save();
    }

    /**
     * Check if the product belongs to the given distributor
     */
    public function belongsToDistributor($distributorId): bool
    {
        return $this->distributor_id === $distributorId;
    }

    /**
     * Get the batch that the product belongs to.
     */
    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
} 