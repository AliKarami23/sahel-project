<?php

namespace App\Http\Middleware;

use App\Models\Order;
use App\Models\Product;
use App\Models\Reservation;
use App\Models\Sans;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckExtradition
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $reserveId = $request->reserve_id;
        $reserve = Reservation::with('products')->find($reserveId);

        if (!$reserve) {
            return response()->json(['error' => 'Order not found.'], 404);
        }

        $order = Order::find($reserve->order_id);

        if (!$order) {
            return response()->json(['error' => 'Order not found.'], 404);
        }

        if ($order->payment_status == 0) {
            return response()->json(['error' => 'The order fee has not been paid.'], 404);
        }

        $productIds = $order->products->pluck('product_id');
        $products = Product::whereIn('id', $productIds)->get();

        $hasExtradition = false;

        foreach ($products as $product) {
            if ($product->extradition == 'yes') {
                $sansCollection = $product->sans;
                foreach ($sansCollection as $sans) {
                    $extraditionDateTime = Carbon::parse("{$sans->date} {$sans->extradition_time}");
                    $currentDateTime = now();

                    if ($currentDateTime->isBefore($extraditionDateTime)) {
                        $hasExtradition = true;
                    } else {
                        return response()->json(['error' => 'Extradition time has ended.'], 403);
                    }
                }
            }
        }
        if ($hasExtradition) {
            return $next($request);
        } else {
            return response()->json(['error' => 'No products with extradition found.'], 403);
        }
    }
}
