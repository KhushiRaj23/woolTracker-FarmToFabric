<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Processor extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'company_name',
        'address',
        'phone',
        'capacity',
        'specialization',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function inventoryItems()
    {
        return $this->hasMany(InventoryItem::class);
    }

    public function getActiveBatches()
    {
        return $this->batches()
            ->where('status', 'processing')
            ->get();
    }

    public function getCompletedBatches()
    {
        return $this->batches()
            ->where('status', 'processed')
            ->get();
    }

    public function getAvailableInventory()
    {
        return $this->inventoryItems()
            ->where('status', 'available')
            ->get();
    }

    public function getTotalProcessedWeight()
    {
        return $this->batches()
            ->where('status', 'processed')
            ->sum('weight');
    }

    public function getAverageProcessingTime()
    {
        return $this->batches()
            ->where('status', 'processed')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, processing_date, completed_date)) as avg_time')
            ->value('avg_time');
    }
}
