<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Farm extends Model
{
    use HasFactory;

    protected $fillable = [
        'farmer_id',
        'name',
        'location',
        'size',
        'contact_person',
        'contact_number',
        'email',
        'description',
        'certification_status',
    ];

    protected $casts = [
        'size' => 'decimal:2',
    ];

    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }
} 