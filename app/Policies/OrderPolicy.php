<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\Retailer;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the retailer can view the order.
     */
    public function view(Retailer $retailer, Order $order): bool
    {
        return $order->retailer_id === $retailer->id;
    }

    /**
     * Determine whether the distributor can update the order.
     */
    public function update(Distributor $distributor, Order $order): bool
    {
        return $distributor->id === $order->distributor_id && $order->status === 'pending';
    }

    /**
     * Determine whether the distributor can delete the order.
     */
    public function delete(Distributor $distributor, Order $order): bool
    {
        return $distributor->id === $order->distributor_id && in_array($order->status, ['pending', 'processing']);
    }
} 