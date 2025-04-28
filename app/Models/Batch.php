<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    protected $fillable = [
        'farm_id',
        'processor_id',
        'distributor_id',
        'batch_number',
        'shearing_date',
        'wool_type',
        'wool_weight',
        'status',
        'quality_grade',
        'notes',
        'processing_date',
        'completed_date',
        'distribution_date',
    ];

    protected $casts = [
        'shearing_date' => 'date',
        'wool_weight' => 'decimal:2',
        'processing_date' => 'datetime',
        'completed_date' => 'datetime',
        'distribution_date' => 'datetime',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function processor()
    {
        return $this->belongsTo(Processor::class);
    }

    public function distributor()
    {
        return $this->belongsTo(Distributor::class);
    }

    public function processingStages()
    {
        return $this->hasMany(ProcessingStage::class);
    }

    public function qualityMetrics()
    {
        return $this->hasMany(QualityMetric::class);
    }

    public function inventoryItems()
    {
        return $this->hasMany(InventoryItem::class);
    }

    public function startProcessing()
    {
        $this->update([
            'status' => 'processing',
            'processing_date' => now(),
        ]);
    }

    public function completeProcessing($qualityGrade, $notes = null)
    {
        $this->update([
            'status' => 'processed',
            'quality_grade' => $qualityGrade,
            'notes' => $notes,
            'completed_date' => now(),
        ]);
    }

    public function getCurrentStage()
    {
        return $this->processingStages()
            ->where('status', 'in_progress')
            ->first();
    }

    public function getNextStage()
    {
        return $this->processingStages()
            ->where('status', 'pending')
            ->orderBy('id')
            ->first();
    }
} 