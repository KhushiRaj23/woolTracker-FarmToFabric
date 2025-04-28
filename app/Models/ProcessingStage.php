<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessingStage extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id',
        'stage_name',
        'description',
        'started_at',
        'completed_at',
        'status',
        'notes',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function qualityMetrics()
    {
        return $this->hasMany(QualityMetric::class, 'stage_id');
    }

    public function start()
    {
        $this->update([
            'status' => 'in_progress',
            'started_at' => now(),
        ]);
    }

    public function complete()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    public function fail($notes = null)
    {
        $this->update([
            'status' => 'failed',
            'notes' => $notes,
        ]);
    }
} 