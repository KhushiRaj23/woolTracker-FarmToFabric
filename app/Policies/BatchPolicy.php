<?php

namespace App\Policies;

use App\Models\Batch;
use App\Models\User;
use App\Models\Farmer;
use Illuminate\Auth\Access\HandlesAuthorization;

class BatchPolicy
{
    use HandlesAuthorization;

    public function view($user, Batch $batch)
    {
        if ($user instanceof Farmer) {
            return $user->id === $batch->farm->farmer_id;
        }
        return false;
    }

    public function create($user)
    {
        return $user instanceof Farmer;
    }

    public function update($user, Batch $batch)
    {
        if ($user instanceof Farmer) {
            return $user->id === $batch->farm->farmer_id;
        }
        return false;
    }

    public function delete($user, Batch $batch)
    {
        if ($user instanceof Farmer) {
            return $user->id === $batch->farm->farmer_id;
        }
        return false;
    }
} 