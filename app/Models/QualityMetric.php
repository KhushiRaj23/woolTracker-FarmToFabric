<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QualityMetric extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id',
        'stage_id',
        'fiber_length',
        'fiber_strength',
        'moisture_content',
        'trash_content',
        'micronaire',
        'uniformity_index',
        'visual_inspection_notes',
        'overall_grade',
    ];

    protected $casts = [
        'fiber_length' => 'decimal:2',
        'fiber_strength' => 'decimal:2',
        'moisture_content' => 'decimal:2',
        'trash_content' => 'decimal:2',
        'micronaire' => 'decimal:2',
        'uniformity_index' => 'decimal:2',
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function stage()
    {
        return $this->belongsTo(ProcessingStage::class, 'stage_id');
    }

    public function calculateOverallGrade()
    {
        $grades = [];
        
        // Fiber Length Grade
        if ($this->fiber_length >= 30) {
            $grades[] = 'A+';
        } elseif ($this->fiber_length >= 28) {
            $grades[] = 'A';
        } elseif ($this->fiber_length >= 26) {
            $grades[] = 'B+';
        } elseif ($this->fiber_length >= 24) {
            $grades[] = 'B';
        } else {
            $grades[] = 'C';
        }

        // Fiber Strength Grade
        if ($this->fiber_strength >= 32) {
            $grades[] = 'A+';
        } elseif ($this->fiber_strength >= 30) {
            $grades[] = 'A';
        } elseif ($this->fiber_strength >= 28) {
            $grades[] = 'B+';
        } elseif ($this->fiber_strength >= 26) {
            $grades[] = 'B';
        } else {
            $grades[] = 'C';
        }

        // Micronaire Grade
        if ($this->micronaire >= 3.7 && $this->micronaire <= 4.2) {
            $grades[] = 'A+';
        } elseif ($this->micronaire >= 3.5 && $this->micronaire <= 4.5) {
            $grades[] = 'A';
        } elseif ($this->micronaire >= 3.3 && $this->micronaire <= 4.7) {
            $grades[] = 'B+';
        } elseif ($this->micronaire >= 3.0 && $this->micronaire <= 5.0) {
            $grades[] = 'B';
        } else {
            $grades[] = 'C';
        }

        // Calculate final grade based on majority
        $gradeCounts = array_count_values($grades);
        arsort($gradeCounts);
        $this->overall_grade = key($gradeCounts);
        $this->save();
    }
} 