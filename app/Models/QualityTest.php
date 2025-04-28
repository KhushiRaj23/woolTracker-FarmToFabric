<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QualityTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'wool_batch_id',
        'test_date',
        'cleanliness_score',
        'strength_score',
        'uniformity_score',
        'color_score',
        'overall_score',
        'notes',
        'tested_by'
    ];

    protected $casts = [
        'test_date' => 'datetime',
        'cleanliness_score' => 'decimal:2',
        'strength_score' => 'decimal:2',
        'uniformity_score' => 'decimal:2',
        'color_score' => 'decimal:2',
        'overall_score' => 'decimal:2',
    ];

    public function woolBatch()
    {
        return $this->belongsTo(WoolBatch::class);
    }

    public function tester()
    {
        return $this->belongsTo(User::class, 'tested_by');
    }
}
