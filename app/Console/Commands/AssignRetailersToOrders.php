<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Retailer;
use Illuminate\Console\Command;

class AssignRetailersToOrders extends Command
{
    protected $signature = 'orders:assign-retailers';
    protected $description = 'Assign retailers to existing orders based on customer email';

    public function handle()
    {
        $this->info('Starting to assign retailers to orders...');

        $orders = Order::whereNull('retailer_id')->get();
        $count = 0;

        foreach ($orders as $order) {
            $retailer = Retailer::where('email', $order->customer_email)->first();
            
            if ($retailer) {
                $order->update(['retailer_id' => $retailer->id]);
                $count++;
                $this->info("Assigned retailer {$retailer->name} to order #{$order->id}");
            }
        }

        $this->info("Completed! Assigned {$count} orders to retailers.");
    }
} 