<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class WoolBatch extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'batch_number',
        'weight',
        'quality',
        'price_per_kg',
        'status',
        'farmer_id',
        'collection_center_id',
        'processing_center_id',
        'distributor_id',
        'notes'
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'price_per_kg' => 'decimal:2',
        'quality' => 'decimal:2',
    ];

    public function farmer()
    {
        return $this->belongsTo(Farmer::class, 'farmer_id');
    }

    public function collectionCenter()
    {
        return $this->belongsTo(User::class, 'collection_center_id');
    }

    public function processingCenter()
    {
        return $this->belongsTo(User::class, 'processing_center_id');
    }

    public function distributor()
    {
        return $this->belongsTo(Distributor::class, 'distributor_id');
    }

    public function qualityTests()
    {
        return $this->hasMany(QualityTest::class);
    }

    public function getTotalPriceAttribute()
    {
        return $this->weight * $this->price_per_kg;
    }
} 