<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\Product;
use App\Models\Sans;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CleanUpOrders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */

    public function handle()
    {
        $oldOrders = Order::where('created_at', '<=', now()->subMinutes(2))->get();

        foreach ($oldOrders as $order) {
            if ($order->Payment_Status == 0 || $order->Payment_Status === false) {
                foreach ($order->product_id as $productId) {
                    $product = Product::find($productId);

                    if ($product) {
                        $reducedCapacityMan = $order->reserves->where('product_id', $productId)->sum('Tickets_Sold_Man');
                        $reducedCapacityWoman = $order->reserves->where('product_id', $productId)->sum('Tickets_Sold_Woman');

                        $order->delete();

                        $sans = Sans::where('product_id', $productId)->first();
                        if ($sans) {
                            $sans->Capacity_remains_Man += $reducedCapacityMan;
                            $sans->Capacity_remains_Woman += $reducedCapacityWoman;
                            $sans->save();
                        }
                    }
                }
            }
        }
    }
}
