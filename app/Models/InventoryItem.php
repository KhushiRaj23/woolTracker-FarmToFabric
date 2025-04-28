<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'processor_id',
        'batch_id',
        'item_code',
        'name',
        'description',
        'quantity',
        'unit',
        'unit_price',
        'quality_grade',
        'status',
        'production_date',
        'expiry_date',
        'storage_location',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'production_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function processor()
    {
        return $this->belongsTo(Processor::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function reserve($quantity)
    {
        if ($this->quantity >= $quantity) {
            $this->update([
                'quantity' => $this->quantity - $quantity,
            ]);
            return true;
        }
        return false;
    }

    public function release($quantity)
    {
        $this->update([
            'quantity' => $this->quantity + $quantity,
        ]);
    }

    public function markAsSold()
    {
        $this->update([
            'status' => 'sold',
        ]);
    }

    public function generateItemCode()
    {
        $prefix = 'INV';
        $date = now()->format('Ymd');
        $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        return "{$prefix}-{$date}-{$random}";
    }
} 