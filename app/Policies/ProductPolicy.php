<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\Distributor;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the distributor can view any models.
     */
    public function viewAny(Distributor $distributor): bool
    {
        return true;
    }

    /**
     * Determine whether the distributor can view the model.
     */
    public function view(Distributor $distributor, Product $product): bool
    {
        return $distributor->id === $product->distributor_id;
    }

    /**
     * Determine whether the distributor can create models.
     */
    public function create(Distributor $distributor): bool
    {
        return true;
    }

    /**
     * Determine whether the distributor can update the model.
     */
    public function update(Distributor $distributor, Product $product): bool
    {
        return $distributor->id === $product->distributor_id;
    }

    /**
     * Determine whether the distributor can delete the model.
     */
    public function delete(Distributor $distributor, Product $product): bool
    {
        return $distributor->id === $product->distributor_id;
    }
} 