<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;
    protected $guard='web';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
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
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    public function processor()
    {
        return $this->hasOne(Processor::class);
    }

    public function isProcessor()
    {
        return $this->role === 'processor';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isFarmer()
    {
        return $this->role === 'farmer';
    }

    public function isDistributor()
    {
        return $this->role === 'distributor';
    }

    public function isRetailer()
    {
        return $this->role === 'retailer';
    }

    public function hasRole($role)
    {
        switch ($role) {
            case 'admin':
                return $this->isAdmin();
            case 'processor':
                return $this->isProcessor();
            case 'farmer':
                return $this->isFarmer();
            case 'distributor':
                return $this->isDistributor();
            case 'retailer':
                return $this->isRetailer();
            default:
                return false;
        }
    }
}
