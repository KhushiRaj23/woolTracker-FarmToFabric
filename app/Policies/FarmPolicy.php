<?php

namespace App\Policies;

use App\Models\Farm;
use App\Models\User;
use App\Models\Farmer;
use Illuminate\Auth\Access\HandlesAuthorization;

class FarmPolicy
{
    use HandlesAuthorization;

    public function view($user, Farm $farm)
    {
        if ($user instanceof Farmer) {
            return $user->id === $farm->farmer_id;
        }
        return false;
    }

    public function create($user)
    {
        return $user instanceof Farmer;
    }

    public function update($user, Farm $farm)
    {
        if ($user instanceof Farmer) {
            return $user->id === $farm->farmer_id;
        }
        return false;
    }

    public function delete($user, Farm $farm)
    {
        if ($user instanceof Farmer) {
            return $user->id === $farm->farmer_id;
        }
        return false;
    }
} 